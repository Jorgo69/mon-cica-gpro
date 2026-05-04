<?php

use App\Enums\PluginHookPoint;
use App\Events\PluginHook;
use App\Models\Plugin;
use App\Services\PluginManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;

// --- Plugin Model ---

it('cree un plugin avec UUID et les bons champs', function () {
    $plugin = Plugin::create([
        'slug' => 'test/my-plugin',
        'name' => 'My Plugin',
        'version' => '1.0.0',
        'author' => 'Test Author',
        'description' => 'A test plugin',
        'provider_class' => 'TestVendor\\MyPlugin\\MyPluginServiceProvider',
        'path' => 'plugins/test/my-plugin',
        'hooks' => ['project.created', 'report.generating'],
        'permissions' => ['export-usaid'],
        'settings' => ['key' => 'value'],
        'is_active' => false,
    ]);

    expect($plugin->id)->toBeString()->toHaveLength(36);
    expect($plugin->slug)->toBe('test/my-plugin');
    expect($plugin->name)->toBe('My Plugin');
    expect($plugin->hooks)->toBe(['project.created', 'report.generating']);
    expect($plugin->permissions)->toBe(['export-usaid']);
    expect($plugin->settings)->toBe(['key' => 'value']);
    expect($plugin->is_active)->toBeFalse();
});

it('retourne le bon plugin via findBySlug', function () {
    $plugin = Plugin::create([
        'slug' => 'vendor/target-plugin',
        'name' => 'Target Plugin',
        'provider_class' => 'Vendor\\Target\\Provider',
        'path' => 'plugins/vendor/target-plugin',
    ]);

    $found = Plugin::findBySlug('vendor/target-plugin');

    expect($found)->not->toBeNull();
    expect($found->id)->toBe($plugin->id);
});

it('retourne null via findBySlug quand le slug est inexistant', function () {
    expect(Plugin::findBySlug('inexistant/plugin'))->toBeNull();
});

it('filtre uniquement les plugins actifs avec le scope active', function () {
    Plugin::create([
        'slug' => 'vendor/active-one',
        'name' => 'Active One',
        'provider_class' => 'Vendor\\One\\Provider',
        'path' => 'plugins/vendor/active-one',
        'is_active' => true,
    ]);

    Plugin::create([
        'slug' => 'vendor/inactive-one',
        'name' => 'Inactive One',
        'provider_class' => 'Vendor\\Two\\Provider',
        'path' => 'plugins/vendor/inactive-one',
        'is_active' => false,
    ]);

    $active = Plugin::active()->get();

    expect($active)->toHaveCount(1);
    expect($active->first()->slug)->toBe('vendor/active-one');
});

// --- Plugin <-> Organization ---

it('active un plugin pour une organisation via enableForOrg', function () {
    $org = createOrg();
    $plugin = Plugin::create([
        'slug' => 'vendor/org-plugin',
        'name' => 'Org Plugin',
        'provider_class' => 'Vendor\\Org\\Provider',
        'path' => 'plugins/vendor/org-plugin',
    ]);

    $plugin->enableForOrg($org->id);

    expect($plugin->isEnabledForOrg($org->id))->toBeTrue();
});

it('retourne false pour isEnabledForOrg quand aucun lien existe', function () {
    $org = createOrg();
    $plugin = Plugin::create([
        'slug' => 'vendor/unlinked',
        'name' => 'Unlinked',
        'provider_class' => 'Vendor\\Unlinked\\Provider',
        'path' => 'plugins/vendor/unlinked',
    ]);

    expect($plugin->isEnabledForOrg($org->id))->toBeFalse();
});

it('desactive un plugin pour une organisation via disableForOrg', function () {
    $org = createOrg();
    $plugin = Plugin::create([
        'slug' => 'vendor/toggle-plugin',
        'name' => 'Toggle Plugin',
        'provider_class' => 'Vendor\\Toggle\\Provider',
        'path' => 'plugins/vendor/toggle-plugin',
    ]);

    $plugin->enableForOrg($org->id);
    expect($plugin->isEnabledForOrg($org->id))->toBeTrue();

    $plugin->disableForOrg($org->id);
    expect($plugin->isEnabledForOrg($org->id))->toBeFalse();
});

// --- listensTo ---

it('retourne true quand le plugin ecoute le hook demande', function () {
    $plugin = Plugin::create([
        'slug' => 'vendor/listener',
        'name' => 'Listener',
        'provider_class' => 'Vendor\\Listener\\Provider',
        'path' => 'plugins/vendor/listener',
        'hooks' => ['project.created', 'report.generating'],
    ]);

    expect($plugin->listensTo('project.created'))->toBeTrue();
});

it('retourne false quand le plugin ne ecoute pas le hook demande', function () {
    $plugin = Plugin::create([
        'slug' => 'vendor/no-listen',
        'name' => 'No Listen',
        'provider_class' => 'Vendor\\NoListen\\Provider',
        'path' => 'plugins/vendor/no-listen',
        'hooks' => ['project.created'],
    ]);

    expect($plugin->listensTo('budget.threshold'))->toBeFalse();
});

// --- PluginManager ---

it('decouvre le plugin USAID dans le dossier plugins', function () {
    PluginManager::resetBooted();

    $discovered = PluginManager::discover();

    expect($discovered)->toBeArray()->not->toBeEmpty();

    $slugs = array_map(fn ($p) => $p->slug, $discovered);
    expect($slugs)->toContain('gpro/usaid-report');

    $usaid = collect($discovered)->firstWhere('slug', 'gpro/usaid-report');
    expect($usaid->name)->toBe('USAID Report Format');
    expect($usaid->version)->toBe('1.0.0');
    expect($usaid->author)->toBe('Cave-Tech');
    expect($usaid->hooks)->toContain('report.generating');
});

it('active un plugin via PluginManager::enable', function () {
    $plugin = Plugin::create([
        'slug' => 'vendor/to-enable',
        'name' => 'To Enable',
        'provider_class' => 'Vendor\\ToEnable\\Provider',
        'path' => 'plugins/vendor/to-enable',
        'is_active' => false,
    ]);

    PluginManager::resetBooted();
    PluginManager::enable($plugin);

    $plugin->refresh();
    expect($plugin->is_active)->toBeTrue();
});

it('desactive un plugin via PluginManager::disable', function () {
    $plugin = Plugin::create([
        'slug' => 'vendor/to-disable',
        'name' => 'To Disable',
        'provider_class' => 'Vendor\\ToDisable\\Provider',
        'path' => 'plugins/vendor/to-disable',
        'is_active' => true,
    ]);

    PluginManager::resetBooted();
    PluginManager::disable($plugin);

    $plugin->refresh();
    expect($plugin->is_active)->toBeFalse();
});

it('dispatche un evenement PluginHook via dispatchHook', function () {
    Event::fake([PluginHook::class]);

    PluginManager::dispatchHook(
        PluginHookPoint::PROJECT_CREATED,
        ['project_id' => 'abc-123'],
        'org-uuid'
    );

    Event::assertDispatched(PluginHook::class, function (PluginHook $event) {
        return $event->hook === PluginHookPoint::PROJECT_CREATED
            && $event->payload['project_id'] === 'abc-123'
            && $event->orgId === 'org-uuid';
    });
});

// --- Securite manifest ---

it('rejette un plugin dont le provider utilise le namespace App', function () {
    $tempDir = base_path('plugins/malicious/evil-plugin');
    File::ensureDirectoryExists($tempDir);
    File::put($tempDir . '/plugin.json', json_encode([
        'name' => 'Evil Plugin',
        'slug' => 'malicious/evil-plugin',
        'provider' => 'App\\Services\\EvilService',
        'hooks' => ['project.created'],
    ]));

    PluginManager::resetBooted();
    $discovered = PluginManager::discover();

    $slugs = array_map(fn ($p) => $p->slug, $discovered);
    expect($slugs)->not->toContain('malicious/evil-plugin');

    // Nettoyage
    File::deleteDirectory(base_path('plugins/malicious'));
});

// --- Commandes artisan ---

it('retourne le code 0 pour la commande gpro:plugin list', function () {
    $this->artisan('gpro:plugin', ['action' => 'list'])
        ->assertExitCode(0);
});

it('decouvre des plugins via la commande gpro:plugin discover', function () {
    $this->artisan('gpro:plugin', ['action' => 'discover'])
        ->assertExitCode(0)
        ->expectsOutputToContain('gpro/usaid-report');
});
