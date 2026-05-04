<?php

namespace App\Services;

use App\Enums\PluginHookPoint;
use App\Events\PluginHook;
use App\Models\Plugin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PluginManager
{
    protected static array $booted = [];

    /**
     * Scan the plugins directory and register new ones in DB.
     */
    public static function discover(): array
    {
        $pluginsPath = base_path('plugins');
        $discovered = [];

        if (!File::isDirectory($pluginsPath)) {
            return $discovered;
        }

        // Scan vendor/plugin-name structure
        foreach (File::directories($pluginsPath) as $vendorDir) {
            foreach (File::directories($vendorDir) as $pluginDir) {
                $manifest = $pluginDir . '/plugin.json';

                if (!File::exists($manifest)) {
                    continue;
                }

                $config = self::readManifest($manifest);
                if (!$config) {
                    continue;
                }

                $slug = $config['slug'] ?? basename($vendorDir) . '/' . basename($pluginDir);

                if (!self::validateManifest($config, $slug)) {
                    continue;
                }

                $existing = Plugin::findBySlug($slug);

                if (!$existing) {
                    $existing = Plugin::create([
                        'slug' => $slug,
                        'name' => $config['name'],
                        'version' => $config['version'] ?? '1.0.0',
                        'author' => $config['author'] ?? null,
                        'description' => $config['description'] ?? null,
                        'provider_class' => $config['provider'],
                        'path' => str_replace(base_path() . '/', '', $pluginDir),
                        'hooks' => $config['hooks'] ?? [],
                        'permissions' => $config['permissions'] ?? [],
                        'settings' => $config['settings'] ?? [],
                        'installed_at' => now(),
                    ]);
                } else {
                    // Update metadata on rediscover (version bump, etc.)
                    $existing->update([
                        'name' => $config['name'],
                        'version' => $config['version'] ?? $existing->version,
                        'author' => $config['author'] ?? $existing->author,
                        'description' => $config['description'] ?? $existing->description,
                        'hooks' => $config['hooks'] ?? $existing->hooks,
                        'permissions' => $config['permissions'] ?? $existing->permissions,
                    ]);
                }

                $discovered[] = $existing;
            }
        }

        return $discovered;
    }

    /**
     * Boot all active plugins (register their service providers).
     */
    public static function boot(): void
    {
        try {
            $plugins = Plugin::active()->get();
        } catch (\Exception $e) {
            // Table might not exist yet (fresh install before migration)
            return;
        }

        foreach ($plugins as $plugin) {
            self::bootPlugin($plugin);
        }
    }

    /**
     * Boot a single plugin.
     */
    public static function bootPlugin(Plugin $plugin): bool
    {
        if (isset(self::$booted[$plugin->slug])) {
            return true;
        }

        $providerClass = $plugin->provider_class;
        $pluginPath = base_path($plugin->path);

        // Security: verify the provider file exists in the expected path
        if (!self::isProviderSafe($providerClass, $pluginPath)) {
            Log::warning("Plugin {$plugin->slug}: provider class not found or unsafe", [
                'class' => $providerClass,
                'path' => $pluginPath,
            ]);
            return false;
        }

        try {
            // Autoload plugin classes
            $srcPath = $pluginPath . '/src';
            if (File::isDirectory($srcPath)) {
                spl_autoload_register(function ($class) use ($providerClass, $srcPath) {
                    $namespace = substr($providerClass, 0, strrpos($providerClass, '\\'));
                    if (str_starts_with($class, $namespace)) {
                        $relative = str_replace($namespace . '\\', '', $class);
                        $file = $srcPath . '/' . str_replace('\\', '/', $relative) . '.php';
                        if (file_exists($file)) {
                            require_once $file;
                        }
                    }
                });
            }

            // Register the service provider
            app()->register($providerClass);

            self::$booted[$plugin->slug] = true;
            return true;
        } catch (\Exception $e) {
            Log::error("Plugin {$plugin->slug}: boot failed", [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Enable a plugin globally (ROOT action).
     */
    public static function enable(Plugin $plugin): void
    {
        $plugin->update(['is_active' => true]);
        self::bootPlugin($plugin);
    }

    /**
     * Disable a plugin globally (ROOT action).
     */
    public static function disable(Plugin $plugin): void
    {
        $plugin->update(['is_active' => false]);
        unset(self::$booted[$plugin->slug]);
    }

    /**
     * Dispatch a hook to all active plugins that listen to it.
     * Returns collected results from listeners.
     */
    public static function dispatchHook(PluginHookPoint $hook, array $payload = [], ?string $orgId = null): array
    {
        $event = new PluginHook($hook, $payload, $orgId);
        event($event);

        return $event->results;
    }

    /**
     * Get available plugins from remote catalog.
     */
    public static function fetchCatalog(): array
    {
        $url = config('gpro.plugin_catalog_url');

        if (!$url) {
            return [];
        }

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)->get($url);

            if ($response->successful()) {
                return $response->json('plugins', []);
            }
        } catch (\Exception $e) {
            Log::warning('Plugin catalog fetch failed', ['error' => $e->getMessage()]);
        }

        return [];
    }

    /**
     * List all installed plugins with their status.
     */
    public static function installed(): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return Plugin::orderBy('name')->get();
        } catch (\Exception $e) {
            return new \Illuminate\Database\Eloquent\Collection();
        }
    }

    // --- Private helpers ---

    private static function readManifest(string $path): ?array
    {
        try {
            $content = File::get($path);
            $config = json_decode($content, true);

            if (!is_array($config)) {
                Log::warning("Plugin manifest invalid JSON: {$path}");
                return null;
            }

            return $config;
        } catch (\Exception $e) {
            Log::warning("Plugin manifest unreadable: {$path}");
            return null;
        }
    }

    private static function validateManifest(array $config, string $slug): bool
    {
        $required = ['name', 'provider'];

        foreach ($required as $field) {
            if (empty($config[$field])) {
                Log::warning("Plugin {$slug}: missing required field '{$field}' in manifest");
                return false;
            }
        }

        // Security: provider class must not reference core app namespaces
        $provider = $config['provider'];
        $forbidden = ['App\\', 'Illuminate\\', 'Laravel\\'];
        foreach ($forbidden as $ns) {
            if (str_starts_with($provider, $ns)) {
                Log::warning("Plugin {$slug}: provider class cannot use namespace {$ns}");
                return false;
            }
        }

        // Validate hooks are known hook points
        if (!empty($config['hooks'])) {
            $validHooks = array_column(PluginHookPoint::cases(), 'value');
            foreach ($config['hooks'] as $hook) {
                if (!in_array($hook, $validHooks)) {
                    Log::warning("Plugin {$slug}: unknown hook '{$hook}'");
                }
            }
        }

        return true;
    }

    private static function isProviderSafe(string $class, string $pluginPath): bool
    {
        // The provider file must exist within the plugin's own directory
        $relative = str_replace('\\', '/', $class);
        $parts = explode('/', $relative);
        $filename = end($parts);

        // Search for the file in the plugin's src directory
        $srcPath = $pluginPath . '/src';
        if (!File::isDirectory($srcPath)) {
            return false;
        }

        $providerFile = $srcPath . '/' . $filename . '.php';

        // Also check with full namespace path
        $namespace = substr($class, 0, strrpos($class, '\\'));
        $classFile = str_replace('\\', '/', substr($class, strlen($namespace) + 1));
        $altFile = $srcPath . '/' . $classFile . '.php';

        return File::exists($providerFile) || File::exists($altFile);
    }

    /**
     * Reset booted state (for testing).
     */
    public static function resetBooted(): void
    {
        self::$booted = [];
    }
}
