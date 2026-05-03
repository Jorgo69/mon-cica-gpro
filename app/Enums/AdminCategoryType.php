<?php

namespace App\Enums;

enum AdminCategoryType: string
{
    case PROJECT_STATUS = 'project_status';
    case ACTIVITY_STATUS = 'activity_status';
    case PROJECT_CATEGORY = 'project_category';
    case BUDGET_CATEGORY = 'budget_category';
    case RESOURCE_TYPE = 'resource_type';
    case DOCUMENT_TYPE = 'document_type';

    public function label(): string
    {
        return __('enums.admin_category_type.' . $this->value);
    }

    public function icon(): string
    {
        return match ($this) {
            self::PROJECT_STATUS => 'folder-kanban',
            self::ACTIVITY_STATUS => 'list-checks',
            self::PROJECT_CATEGORY => 'tags',
            self::BUDGET_CATEGORY => 'banknote',
            self::RESOURCE_TYPE => 'box',
            self::DOCUMENT_TYPE => 'file-text',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PROJECT_STATUS => 'accent',
            self::ACTIVITY_STATUS => 'blue',
            self::PROJECT_CATEGORY => 'emerald',
            self::BUDGET_CATEGORY => 'amber',
            self::RESOURCE_TYPE => 'violet',
            self::DOCUMENT_TYPE => 'slate',
        };
    }
}
