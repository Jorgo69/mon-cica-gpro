<?php

namespace App\Enums;

enum PluginHookPoint: string
{
    case PROJECT_CREATED = 'project.created';
    case PROJECT_EXPORTED = 'project.exported';
    case ACTIVITY_COMPLETED = 'activity.completed';
    case BUDGET_THRESHOLD = 'budget.threshold';
    case REPORT_GENERATING = 'report.generating';
    case DASHBOARD_WIDGETS = 'dashboard.widgets';
    case SETTINGS_TABS = 'settings.tabs';
    case SIDEBAR_ITEMS = 'sidebar.items';

    public function label(): string
    {
        return match ($this) {
            self::PROJECT_CREATED => __('enums.plugin_hook.project_created'),
            self::PROJECT_EXPORTED => __('enums.plugin_hook.project_exported'),
            self::ACTIVITY_COMPLETED => __('enums.plugin_hook.activity_completed'),
            self::BUDGET_THRESHOLD => __('enums.plugin_hook.budget_threshold'),
            self::REPORT_GENERATING => __('enums.plugin_hook.report_generating'),
            self::DASHBOARD_WIDGETS => __('enums.plugin_hook.dashboard_widgets'),
            self::SETTINGS_TABS => __('enums.plugin_hook.settings_tabs'),
            self::SIDEBAR_ITEMS => __('enums.plugin_hook.sidebar_items'),
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::PROJECT_CREATED => 'Fired after a new project is created',
            self::PROJECT_EXPORTED => 'Fired before PDF/DOCX export, allows custom templates',
            self::ACTIVITY_COMPLETED => 'Fired when an activity reaches 100%',
            self::BUDGET_THRESHOLD => 'Fired when budget usage exceeds threshold',
            self::REPORT_GENERATING => 'Fired during report generation, allows custom sections',
            self::DASHBOARD_WIDGETS => 'Allows plugins to inject dashboard widgets',
            self::SETTINGS_TABS => 'Allows plugins to add settings tabs',
            self::SIDEBAR_ITEMS => 'Allows plugins to add sidebar menu items',
        };
    }
}
