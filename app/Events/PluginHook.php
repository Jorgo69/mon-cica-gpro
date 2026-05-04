<?php

namespace App\Events;

use App\Enums\PluginHookPoint;
use Illuminate\Foundation\Events\Dispatchable;

class PluginHook
{
    use Dispatchable;

    public function __construct(
        public PluginHookPoint $hook,
        public array $payload = [],
        public ?string $orgId = null,
    ) {}

    /**
     * Collected results from listeners (e.g. widget HTML, sidebar items).
     */
    public array $results = [];

    public function addResult(string $pluginSlug, mixed $data): void
    {
        $this->results[$pluginSlug] = $data;
    }
}
