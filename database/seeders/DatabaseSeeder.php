<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appeler le seeder qui va créer les utilisateurs et les rôles
        $this->call([
            PermissionSeeder::class,
            OrganizationSeeder::class,
            UserSeeder::class,
            ProjectTypeSeeder::class,
            GeneralAdministrationSeeder::class,
        ]);
    }
}
