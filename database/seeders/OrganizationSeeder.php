<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Enums\OrganizationStatus;
use App\Enums\OrganizationType;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        Organization::firstOrCreate(
            ['slug' => 'cica-pro'],
            [
                'name'    => 'CICA PRO',
                'type'    => OrganizationType::HEADQUARTERS,
                'status'  => OrganizationStatus::ACTIVE,
                'country' => 'BJ',
                'location' => [
                    'city'    => 'Cotonou',
                    'region'  => 'Littoral',
                    'adresse' => 'Boulevard Saint-Michel',
                ],
                'contact' => [
                    'email'   => 'contact@cica-pro.org',
                    'website' => 'https://cica-pro.org',
                ],
            ]
        );

        if (app()->environment('local', 'testing')) {
            Organization::factory()->count(3)->create();
        }
    }
}
