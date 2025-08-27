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
        // 1. Créer le département par défaut
        // $defaultDepartment = Department::firstOrCreate(
        //     ['name' => 'Direction'],
        //     [
        //         'id' => (string) Str::uuid(),
        //         'description' => 'Département de la direction générale'
        //     ]
        // );

        // 2. Créer le rôle Administrateur s'il n'existe pas
        // $adminRole = Role::firstOrCreate(
        //     ['name' => 'Administrateur'],
        //     [
        //         'id' => (string) Str::uuid(),
        //         'description' => 'Accès complet au système'
        //     ]
        // );

        // 3. Créer l'utilisateur Administrateur s'il n'existe pas
        // Le nom de domaine de l'email est générique, vous pouvez le modifier
        User::firstOrCreate(
            ['email' => 'admin@localhost.com'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Admin',
                'password' => Hash::make('password'), // Mot de passe par défaut 'password'
                'email_verified_at' => now(),
                'sexe' => 'Homme',
                'role' => 'Administrateur',
                'department' => 'Informatique',
            ]
        );
    }
}
