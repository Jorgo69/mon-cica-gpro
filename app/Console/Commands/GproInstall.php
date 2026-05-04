<?php

namespace App\Console\Commands;

use App\Enums\AccountType;
use App\Enums\OrganizationStatus;
use App\Enums\PermissionLevel;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GproInstall extends Command
{
    protected $signature = 'gpro:install
        {--fresh : Run migrate:fresh instead of migrate (destroys all data)}
        {--seed : Run seeders after migration}';

    protected $description = 'Install GPRO: run migrations, seed, and create your first organization + admin account';

    public function handle(): int
    {
        $this->info('');
        $this->info('  ╔══════════════════════════════════════╗');
        $this->info('  ║     CICA-GPRO — Installation         ║');
        $this->info('  ║     Gestion de projets pour ONG      ║');
        $this->info('  ╚══════════════════════════════════════╝');
        $this->info('');

        // Step 1: Migrations
        $this->info('1/4 — Base de donnees');
        if ($this->option('fresh')) {
            if (!$this->confirm('migrate:fresh va SUPPRIMER toutes les donnees. Continuer ?')) {
                $this->warn('Installation annulee.');
                return 1;
            }
            Artisan::call('migrate:fresh', ['--force' => true]);
        } else {
            Artisan::call('migrate', ['--force' => true]);
        }
        $this->info('   Migrations OK.');

        // Step 2: Seeders
        $this->info('2/4 — Donnees de base');
        if ($this->option('seed') || $this->confirm('Charger les donnees de base (roles, permissions, categories) ?', true)) {
            Artisan::call('db:seed', ['--force' => true]);
            $this->info('   Seeders OK.');
        } else {
            // At minimum seed permissions
            Artisan::call('db:seed', ['--class' => 'PermissionSeeder', '--force' => true]);
            $this->info('   Permissions OK.');
        }

        // Step 3: Check if admin already exists
        $existingAdmin = User::where('role', AccountType::ORG_ADMIN)->first();
        if ($existingAdmin) {
            $this->warn("   Un administrateur existe deja : {$existingAdmin->email}");
            if (!$this->confirm('Creer un autre administrateur quand meme ?', false)) {
                $this->finalSteps();
                return 0;
            }
        }

        // Step 4: Create org + admin
        $this->info('3/4 — Votre organisation');
        $orgName = $this->ask('Nom de votre organisation', 'Mon Organisation');

        $this->info('4/4 — Votre compte administrateur');
        $name = $this->ask('Votre nom complet');
        $email = $this->ask('Votre email');

        while (User::where('email', $email)->exists()) {
            $this->error("   L'email {$email} est deja utilise.");
            $email = $this->ask('Choisissez un autre email');
        }

        $password = $this->secret('Mot de passe (min 8 caracteres)');
        while (strlen($password) < 8) {
            $this->error('   Le mot de passe doit faire au moins 8 caracteres.');
            $password = $this->secret('Mot de passe');
        }

        // Create organization
        $org = Organization::create([
            'name' => $orgName,
            'slug' => Str::slug($orgName),
            'status' => OrganizationStatus::ACTIVE,
        ]);

        // Create admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => AccountType::ORG_ADMIN,
            'organization_id' => $org->id,
            'email_verified_at' => now(),
        ]);

        // Set as owner
        $org->update(['owner_user_id' => $user->id]);

        // Assign Spatie role + permissions
        $level = PermissionLevel::ADMIN;
        $user->assignRole($level->spatieRole());
        $user->syncPermissions($level->permissions());

        $this->info('');
        $this->info('   Organisation creee : ' . $orgName);
        $this->info('   Administrateur : ' . $email);

        $this->finalSteps();

        return 0;
    }

    protected function finalSteps(): void
    {
        // Storage link
        Artisan::call('storage:link', [], $this->output);

        // Clear caches
        Artisan::call('optimize:clear', [], $this->output);

        $this->info('');
        $this->info('  ✓ Installation terminee !');
        $this->info('');
        $this->info('  Lancez le serveur : php artisan serve');
        $this->info('  Puis ouvrez : http://localhost:8000');
        $this->info('');
    }
}
