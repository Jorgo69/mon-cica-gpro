<?php

namespace GproPlugins\UsaidReport;

use App\Enums\PluginHookPoint;
use App\Events\PluginHook;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class UsaidReportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Listen for report generation hook
        Event::listen(PluginHook::class, function (PluginHook $event) {
            if ($event->hook === PluginHookPoint::REPORT_GENERATING) {
                $event->addResult('gpro/usaid-report', [
                    'format' => 'usaid',
                    'label' => 'USAID Performance Report',
                    'sections' => [
                        'performance_indicator_table',
                        'results_framework',
                        'activity_monitoring',
                        'budget_pipeline',
                    ],
                ]);
            }

            if ($event->hook === PluginHookPoint::PROJECT_EXPORTED) {
                // Could add USAID-specific data to the export payload
                $event->addResult('gpro/usaid-report', [
                    'extra_template' => 'usaid-report::export',
                ]);
            }
        });

        // Register plugin views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'usaid-report');
    }
}
