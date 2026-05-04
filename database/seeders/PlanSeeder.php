<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'price' => 0,
                'currency' => 'FCFA',
                'billing_period' => 'month',
                'max_projects' => 2,
                'max_members' => 5,
                'features' => ['basic_export', 'logframe'],
                'is_default' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price' => 15000,
                'currency' => 'FCFA',
                'billing_period' => 'month',
                'max_projects' => 20,
                'max_members' => 50,
                'features' => ['basic_export', 'logframe', 'pdf_export', 'excel_export', 'share_link', 'templates', 'indicators', 'budget_tracking'],
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 45000,
                'currency' => 'FCFA',
                'billing_period' => 'month',
                'max_projects' => -1,
                'max_members' => -1,
                'features' => ['basic_export', 'logframe', 'pdf_export', 'excel_export', 'share_link', 'templates', 'indicators', 'budget_tracking', 'api_access', 'priority_support', 'multi_currency'],
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $data) {
            Plan::updateOrCreate(
                ['slug' => $data['slug']],
                $data,
            );
        }
    }
}
