<?php

namespace App\Livewire\V1\System;

use App\Livewire\Traits\WithToastNotifications;
use App\Models\Plugin;
use App\Services\PluginManager;
use Livewire\Component;

class PluginMarketplaceLivewire extends Component
{
    use WithToastNotifications;

    public string $search = '';
    public array $catalog = [];

    public function mount(): void
    {
        $this->catalog = PluginManager::fetchCatalog();
    }

    public function discover(): void
    {
        $discovered = PluginManager::discover();
        $count = count($discovered);
        $this->notifyToast('success', "{$count} plugin(s) scanne(s).");
    }

    public function toggleActive(string $pluginId): void
    {
        $plugin = Plugin::find($pluginId);
        if (!$plugin) return;

        if ($plugin->is_active) {
            PluginManager::disable($plugin);
            $this->notifyToast('info', "{$plugin->name} desactive.");
        } else {
            $success = PluginManager::enable($plugin);
            if ($success) {
                $this->notifyToast('success', "{$plugin->name} active.");
            } else {
                $this->notifyToast('error', "Echec d'activation de {$plugin->name}.");
            }
        }
    }

    public function uninstall(string $pluginId): void
    {
        $plugin = Plugin::find($pluginId);
        if (!$plugin) return;

        if ($plugin->is_active) {
            PluginManager::disable($plugin);
        }

        $name = $plugin->name;
        $plugin->delete();
        $this->notifyToast('success', "{$name} desinstalle.");
    }

    public function render()
    {
        $plugins = Plugin::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->get();

        return view('livewire.v1.system.plugin-marketplace-livewire', [
            'plugins' => $plugins,
            'catalog' => $this->catalog,
        ]);
    }
}
