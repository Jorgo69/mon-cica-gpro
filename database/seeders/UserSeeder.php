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
            ['email' => 'admin@cave-tech.com'],
            [
                'name' => 'Jean Dupont',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'sexe' => 'Homme',
                'role' => \App\Enums\AccountType::ADMIN,
                'department' => 'Informatique & Systèmes',
                'telephone' => '+229 97 00 01 02',
                'pays' => 'Bénin',
                'ville' => 'Cotonou',
            ]
        );

        // 2. Superviseurs
        $supervisors = [
            ['name' => 'Alice Dossou', 'email' => 'alice.d@cpro.org', 'sexe' => 'Femme'],
            ['name' => 'Marc Toko', 'email' => 'marc.toko@cpro.org', 'sexe' => 'Homme'],
        ];

        foreach ($supervisors as $supervisor) {
            User::firstOrCreate(
                ['email' => $supervisor['email']],
                [
                    'name' => $supervisor['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'sexe' => $supervisor['sexe'],
                    'role' => \App\Enums\AccountType::SUPERVISOR,
                    'department' => 'Direction stratégique',
                    'pays' => 'Bénin',
                    'ville' => 'Porto-Novo',
                ]
            );
        }

        // 3. Responsables de Projet
        User::firstOrCreate(
            ['email' => 'p.manager@cpro.org'],
            [
                'name' => 'Sophie Koumé',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'sexe' => 'Femme',
                'role' => \App\Enums\AccountType::MANAGER,
                'department' => 'Gestion de Projets',
                'telephone' => '+229 96 11 22 33',
                'pays' => 'Bénin',
                'ville' => 'Abomey-Calavi',
            ]
        );

        // 4. Membres / Agents de terrain
        $members = [
            ['name' => 'Idriss Gnonlon', 'email' => 'idriss.g@cpro.org', 'sexe' => 'Homme'],
            ['name' => 'Carine Sika', 'email' => 'carine.s@cpro.org', 'sexe' => 'Femme'],
            ['name' => 'Baki Bio', 'email' => 'baki.bio@cpro.org', 'sexe' => 'Homme'],
        ];

        foreach ($members as $member) {
            User::firstOrCreate(
                ['email' => $member['email']],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'sexe' => $member['sexe'],
                    'role' => \App\Enums\AccountType::MEMBER,
                    'department' => 'Terrain & Opérations',
                    'pays' => 'Bénin',
                    'ville' => 'Parakou',
                ]
            );
        }
    }
}
