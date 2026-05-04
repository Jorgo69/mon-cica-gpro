<?php

namespace App\Console\Commands;

use App\Models\Plugin;
use App\Services\PluginManager;
use Illuminate\Console\Command;

class PluginCommand extends Command
{
    protected $signature = 'gpro:plugin
        {action=list : Action to perform (list, discover, enable, disable)}
        {slug? : Plugin slug (for enable/disable)}';

    protected $description = 'Manage GPRO plugins';

    public function handle(): int
    {
        $action = $this->argument('action');

        return match ($action) {
            'list' => $this->listPlugins(),
            'discover' => $this->discoverPlugins(),
            'enable' => $this->enablePlugin(),
            'disable' => $this->disablePlugin(),
            default => $this->error("Unknown action: {$action}") ?? self::FAILURE,
        };
    }

    private function listPlugins(): int
    {
        $plugins = PluginManager::installed();

        if ($plugins->isEmpty()) {
            $this->info('No plugins installed. Run `php artisan gpro:plugin discover` to scan.');
            return self::SUCCESS;
        }

        $this->table(
            ['Slug', 'Name', 'Version', 'Author', 'Active', 'Hooks'],
            $plugins->map(fn (Plugin $p) => [
                $p->slug,
                $p->name,
                $p->version,
                $p->author ?? '-',
                $p->is_active ? '<fg=green>Yes</>' : '<fg=red>No</>',
                implode(', ', $p->hooks ?? []),
            ])
        );

        return self::SUCCESS;
    }

    private function discoverPlugins(): int
    {
        $this->info('Scanning plugins directory...');

        $discovered = PluginManager::discover();

        if (empty($discovered)) {
            $this->warn('No plugins found in ' . base_path(config('gpro.plugins.path', 'plugins')));
            $this->line('Expected structure: plugins/vendor-name/plugin-name/plugin.json');
            return self::SUCCESS;
        }

        foreach ($discovered as $plugin) {
            $this->line("  <fg=green>✓</> {$plugin->slug} (v{$plugin->version})");
        }

        $this->info(count($discovered) . ' plugin(s) discovered.');
        return self::SUCCESS;
    }

    private function enablePlugin(): int
    {
        $slug = $this->argument('slug');
        if (!$slug) {
            $this->error('Plugin slug required. Usage: gpro:plugin enable vendor/name');
            return self::FAILURE;
        }

        $plugin = Plugin::findBySlug($slug);
        if (!$plugin) {
            $this->error("Plugin not found: {$slug}");
            return self::FAILURE;
        }

        PluginManager::enable($plugin);
        $this->info("Plugin {$plugin->name} enabled.");
        return self::SUCCESS;
    }

    private function disablePlugin(): int
    {
        $slug = $this->argument('slug');
        if (!$slug) {
            $this->error('Plugin slug required. Usage: gpro:plugin disable vendor/name');
            return self::FAILURE;
        }

        $plugin = Plugin::findBySlug($slug);
        if (!$plugin) {
            $this->error("Plugin not found: {$slug}");
            return self::FAILURE;
        }

        PluginManager::disable($plugin);
        $this->info("Plugin {$plugin->name} disabled.");
        return self::SUCCESS;
    }
}
