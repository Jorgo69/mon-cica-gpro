<?php

namespace App\Services;

use App\Models\Webhook;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookDispatcher
{
    /**
     * Dispatch an event to all matching webhooks for the given organization.
     */
    public static function dispatch(string $orgId, string $event, array $payload): void
    {
        $webhooks = Webhook::where('organization_id', $orgId)
            ->where('is_active', true)
            ->get()
            ->filter(fn ($wh) => $wh->listensTo($event));

        foreach ($webhooks as $webhook) {
            dispatch(function () use ($webhook, $event, $payload) {
                self::send($webhook, $event, $payload);
            })->afterResponse();
        }
    }

    /**
     * Send a single webhook request.
     */
    protected static function send(Webhook $webhook, string $event, array $payload): void
    {
        $body = [
            'event' => $event,
            'timestamp' => now()->toISOString(),
            'data' => $payload,
        ];

        $jsonBody = json_encode($body);
        $signature = hash_hmac('sha256', $jsonBody, $webhook->secret);

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-GPRO-Event' => $event,
                    'X-GPRO-Signature' => $signature,
                    'X-GPRO-Timestamp' => now()->toISOString(),
                    'User-Agent' => 'GPRO-Webhook/1.0',
                ])
                ->withBody($jsonBody, 'application/json')
                ->post($webhook->url);

            $webhook->update([
                'last_triggered_at' => now(),
                'failure_count' => $response->successful() ? 0 : $webhook->failure_count + 1,
            ]);

            if (!$response->successful()) {
                Log::warning('Webhook failed', [
                    'webhook_id' => $webhook->id,
                    'url' => $webhook->url,
                    'event' => $event,
                    'status' => $response->status(),
                ]);
            }

            // Auto-disable after 10 consecutive failures
            if ($webhook->failure_count >= 10) {
                $webhook->update(['is_active' => false]);
                Log::warning('Webhook auto-disabled after 10 failures', ['webhook_id' => $webhook->id]);
            }
        } catch (\Exception $e) {
            $webhook->increment('failure_count');
            Log::warning('Webhook exception', [
                'webhook_id' => $webhook->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
