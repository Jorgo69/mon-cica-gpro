<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CacheInvalidationObserver
{
    public function saved(Model $model): void
    {
        $this->clearDashboardCache($model);
    }

    public function deleted(Model $model): void
    {
        $this->clearDashboardCache($model);
    }

    protected function clearDashboardCache(Model $model): void
    {
        // Clear dashboard cache for all users in the org
        // Uses tag-less approach: we clear by pattern prefix
        $orgId = $model->organization_id ?? null;
        $creatorId = $model->creator_user_id ?? $model->responsible_user_id ?? null;

        if ($creatorId) {
            // Clear specific user cache (all periods)
            $patterns = [
                "dashboard_stats_{$creatorId}_",
                "dashboard_stats_{$creatorId}__",
            ];
            foreach ($patterns as $pattern) {
                // For file cache, we can't do prefix delete easily
                // So we clear the specific known keys
                Cache::forget("dashboard_stats_{$creatorId}__");
            }
        }
    }
}
