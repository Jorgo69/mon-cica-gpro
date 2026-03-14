<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Administrateur IT
        User::firstOrCreate(
            ['email' => 'admin@localhost.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'IT Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'sexe' => 'Homme',
                'role' => 'Administrateur',
                'department' => 'Informatique',
            ]
        );

        // 2. Superviseurs
        for ($i = 1; $i <= 2; $i++) {
            User::firstOrCreate(
                ['email' => "superviseur{$i}@localhost.com"],
                [
                    'id' => (string) Str::uuid(),
                    'name' => "Superviseur {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'sexe' => $i % 2 == 0 ? 'Femme' : 'Homme',
                    'role' => 'Superviseur',
                    'department' => 'Direction',
                ]
            );
        }

        // 3. Membres / Agents de terrain
        for ($i = 1; $i <= 3; $i++) {
            User::firstOrCreate(
                ['email' => "membre{$i}@localhost.com"],
                [
                    'id' => (string) Str::uuid(),
                    'name' => "Membre ONG {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'sexe' => $i % 2 == 0 ? 'Femme' : 'Homme',
                    'role' => 'membre',
                    'department' => 'Opérations',
                ]
            );
        }

        // 4. Responsable de Projet
        User::firstOrCreate(
            ['email' => 'manager@localhost.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Project Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'sexe' => 'Femme',
                'role' => 'Responsable',
                'department' => 'Gestion de Projets',
            ]
        );
    }
}
