<?php

use App\Models\Webhook;
use App\Services\WebhookDispatcher;
use Illuminate\Support\Facades\Http;

test('webhook cree avec un secret auto-genere de 64 caracteres', function () {
    $org = createOrg();

    $webhook = Webhook::create([
        'organization_id' => $org->id,
        'url' => 'https://example.com/hook',
        'events' => ['project.created'],
    ]);

    expect($webhook->secret)->toBeString()
        ->and(strlen($webhook->secret))->toBe(64);
});

test('dispatch ignore les webhooks qui n ecoutent pas l evenement', function () {
    Http::fake();

    $org = createOrg();
    Webhook::create([
        'organization_id' => $org->id,
        'url' => 'https://example.com/hook',
        'events' => ['activity.completed'],
    ]);

    WebhookDispatcher::dispatch($org->id, 'project.created', ['id' => 'abc']);
    app()->terminate();

    Http::assertNothingSent();
});

test('webhook desactive apres 10 echecs consecutifs', function () {
    Http::fake(['https://example.com/hook' => Http::response('Server Error', 500)]);

    $org = createOrg();
    $webhook = Webhook::create([
        'organization_id' => $org->id,
        'url' => 'https://example.com/hook',
        'events' => ['project.created'],
        'failure_count' => 9,
    ]);

    $method = new ReflectionMethod(WebhookDispatcher::class, 'send');
    $method->setAccessible(true);
    $method->invoke(null, $webhook, 'project.created', ['id' => 'abc']);

    $webhook->refresh();

    expect($webhook->failure_count)->toBeGreaterThanOrEqual(10)
        ->and($webhook->is_active)->toBeFalse();
});

test('signature HMAC-SHA256 correcte dans le header X-GPRO-Signature', function () {
    Http::fake(['https://example.com/hook' => Http::response('OK', 200)]);

    $org = createOrg();
    $webhook = Webhook::create([
        'organization_id' => $org->id,
        'url' => 'https://example.com/hook',
        'secret' => 'test-secret-key-1234567890',
        'events' => ['project.created'],
    ]);

    $method = new ReflectionMethod(WebhookDispatcher::class, 'send');
    $method->setAccessible(true);
    $method->invoke(null, $webhook, 'project.created', ['id' => 'abc']);

    Http::assertSent(function ($request) use ($webhook) {
        $body = $request->body();
        $expectedSignature = hash_hmac('sha256', $body, $webhook->secret);

        return $request->hasHeader('X-GPRO-Signature')
            && $request->header('X-GPRO-Signature')[0] === $expectedSignature
            && $request->hasHeader('X-GPRO-Event')
            && $request->header('X-GPRO-Event')[0] === 'project.created';
    });
});
