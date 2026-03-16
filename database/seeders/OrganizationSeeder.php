<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Enums\OrganizationStatus;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::firstOrCreate(
            ['slug' => 'cica-pro'],
            [
                'name' => 'CICA PRO',
                'status' => OrganizationStatus::ACTIVE,
            ]
        );

        if (app()->environment('local', 'testing')) {
            Organization::factory()->count(3)->create();
        }
    }
}
