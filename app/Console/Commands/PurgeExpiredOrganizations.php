<?php

namespace App\Console\Commands;

use App\Models\Organization;
use App\Services\GdprDeleteService;
use Illuminate\Console\Command;

class PurgeExpiredOrganizations extends Command
{
    protected $signature = 'gpro:purge-expired-orgs';
    protected $description = 'Hard-delete organizations that were scheduled for deletion 30+ days ago';

    public function handle(): int
    {
        $orgs = Organization::all()->filter(function ($org) {
            $scheduledAt = $org->getMeta('deletion_scheduled_at');
            if (!$scheduledAt) return false;

            return now()->diffInDays($scheduledAt) >= 30;
        });

        if ($orgs->isEmpty()) {
            $this->info('No expired organizations to purge.');
            return 0;
        }

        foreach ($orgs as $org) {
            $this->info("Purging: {$org->name} (scheduled {$org->getMeta('deletion_scheduled_at')})");
            GdprDeleteService::executeOrgDeletion($org);
        }

        $this->info("Purged {$orgs->count()} organization(s).");
        return 0;
    }
}
