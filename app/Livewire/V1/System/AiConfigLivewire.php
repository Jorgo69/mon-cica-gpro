<?php

namespace App\Livewire\V1\System;

use App\Enums\AiProvider;
use App\Livewire\Traits\WithToastNotifications;
use App\Models\AiConfig;
use Livewire\Component;

class AiConfigLivewire extends Component
{
    use WithToastNotifications;

    public ?string $configId = null;
    public string $provider = 'groq';
    public string $apiKey = '';
    public string $baseUrl = '';
    public string $model = '';
    public bool $enabled = true;
    public ?string $maskedKey = null;

    // Global fallback from .env
    public bool $hasEnvGroq = false;
    public bool $hasEnvGemini = false;

    public function mount()
    {
        $this->hasEnvGroq = !empty(config('gpro.ai.groq_api_key'));
        $this->hasEnvGemini = !empty(config('gpro.ai.gemini_api_key'));

        // Load existing global config (configurable_type = null, stored as "global")
        $config = AiConfig::where('configurable_type', 'global')
            ->where('configurable_id', 'system')
            ->first();

        if ($config) {
            $this->configId = $config->id;
            $this->provider = $config->provider->value;
            $this->baseUrl = $config->base_url ?? '';
            $this->model = $config->model ?? '';
            $this->enabled = $config->enabled;
            $this->maskedKey = $config->masked_key;
        }
    }

    public function updatedProvider()
    {
        $providerEnum = AiProvider::tryFrom($this->provider);
        if ($providerEnum) {
            $this->model = $providerEnum->defaultModel();
            $this->baseUrl = $providerEnum === AiProvider::CUSTOM ? '' : '';
        }
    }

    public function save()
    {
        $this->validate([
            'provider' => 'required|in:' . implode(',', array_column(AiProvider::cases(), 'value')),
            'model' => 'nullable|string|max:100',
            'baseUrl' => 'nullable|url|max:255',
        ]);

        $data = [
            'configurable_type' => 'global',
            'configurable_id' => 'system',
            'provider' => $this->provider,
            'base_url' => $this->baseUrl ?: null,
            'model' => $this->model ?: null,
            'enabled' => $this->enabled,
        ];

        // Only update API key if a new one was provided
        if (!empty($this->apiKey)) {
            $data['api_key_encrypted'] = encrypt($this->apiKey);
        }

        $config = AiConfig::updateOrCreate(
            ['configurable_type' => 'global', 'configurable_id' => 'system'],
            $data
        );

        $this->configId = $config->id;
        $this->maskedKey = $config->masked_key;
        $this->apiKey = '';

        $this->notifyToast('success', __('common.saved'));
    }

    public function testConnection()
    {
        $config = AiConfig::where('configurable_type', 'global')
            ->where('configurable_id', 'system')
            ->first();

        if (!$config || !$config->isUsable()) {
            $this->notifyToast('danger', __('ai.config.no_key'));
            return;
        }

        $providerEnum = $config->provider;
        $apiKey = $config->api_key;
        $baseUrl = $config->getEffectiveBaseUrl();
        $model = $config->getEffectiveModel();

        try {
            if ($providerEnum === AiProvider::GEMINI) {
                $url = rtrim($baseUrl, '/') . "/models/{$model}:generateContent?key={$apiKey}";
                $response = \Illuminate\Support\Facades\Http::timeout(10)->post($url, [
                    'contents' => [['parts' => [['text' => 'Say OK']]]],
                    'generationConfig' => ['maxOutputTokens' => 10],
                ]);
            } elseif ($providerEnum === AiProvider::ANTHROPIC) {
                $url = rtrim($baseUrl, '/') . '/messages';
                $response = \Illuminate\Support\Facades\Http::timeout(10)
                    ->withHeaders([
                        'x-api-key' => $apiKey,
                        'anthropic-version' => '2023-06-01',
                        'content-type' => 'application/json',
                    ])
                    ->post($url, [
                        'model' => $model,
                        'max_tokens' => 10,
                        'messages' => [['role' => 'user', 'content' => 'Say OK']],
                    ]);
            } elseif ($providerEnum === AiProvider::COHERE) {
                $url = rtrim($baseUrl, '/') . '/chat';
                $response = \Illuminate\Support\Facades\Http::timeout(10)
                    ->withHeaders(['Authorization' => 'Bearer ' . $apiKey])
                    ->post($url, [
                        'model' => $model,
                        'message' => 'Say OK',
                    ]);
            } else {
                $url = rtrim($baseUrl, '/') . '/chat/completions';
                $response = \Illuminate\Support\Facades\Http::timeout(10)
                    ->withHeaders(['Authorization' => 'Bearer ' . $apiKey])
                    ->post($url, [
                        'model' => $model,
                        'messages' => [['role' => 'user', 'content' => 'Say OK']],
                        'max_tokens' => 10,
                    ]);
            }

            if ($response->successful()) {
                $this->notifyToast('success', __('ai.config.test_success'));
            } else {
                $this->notifyToast('danger', __('ai.config.test_failed') . ' (' . $response->status() . ')');
            }
        } catch (\Exception $e) {
            $this->notifyToast('danger', __('ai.config.test_failed') . ': ' . $e->getMessage());
        }
    }

    public function deleteConfig()
    {
        AiConfig::where('configurable_type', 'global')
            ->where('configurable_id', 'system')
            ->delete();

        $this->configId = null;
        $this->provider = 'groq';
        $this->apiKey = '';
        $this->baseUrl = '';
        $this->model = '';
        $this->enabled = true;
        $this->maskedKey = null;

        $this->notifyToast('success', __('ai.config.deleted'));
    }

    public function render()
    {
        return view('livewire.v1.system.ai-config-livewire', [
            'providers' => AiProvider::cases(),
        ]);
    }
}
