<?php

namespace Database\Seeders;

use App\Enums\ActivityStatus;
use App\Enums\ProjectStatus;
use App\Models\Activity;
use App\Models\Budget;
use App\Models\LogicalFramework;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Result;
use App\Models\SpecificObjective;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoProjectSeeder extends Seeder
{
    public function run(): void
    {
        $org = Organization::where('slug', 'projexia-international')->first();
        if (! $org) return;

        $admin = User::where('email', 'admin@projexia.org')->first();
        if (! $admin) return;

        $project = Project::create([
            'organization_id' => $org->id,
            'creator_user_id' => $admin->id,
            'project_code' => 'DEMO-2026-001',
            'title' => 'Formation des agriculteurs en techniques durables',
            'short_title' => 'AgriDurable',
            'description' => 'Programme de formation pour 500 agriculteurs dans la region du Zou sur les techniques agricoles durables et la gestion des ressources naturelles.',
            'status' => ProjectStatus::ACTIVE,
            'currency' => \App\Enums\Currency::XOF,
            'start_date' => now()->subMonths(2),
            'end_date' => now()->addMonths(10),
            'is_template' => true,
        ]);

        $lf = LogicalFramework::create([
            'organization_id' => $org->id,
            'project_id' => $project->id,
            'creator_user_id' => $admin->id,
            'general_objective' => 'Ameliorer la securite alimentaire et les revenus de 500 familles agricoles du Zou',
        ]);

        // Objectif Specifique 1
        $so1 = SpecificObjective::create([
            'organization_id' => $org->id,
            'logical_framework_id' => $lf->id,
            'creator_user_id' => $admin->id,
            'description' => 'Renforcer les capacites techniques de 500 agriculteurs en pratiques agricoles durables',
        ]);

        $r1 = Result::create([
            'organization_id' => $org->id,
            'specific_objective_id' => $so1->id,
            'creator_user_id' => $admin->id,
            'description' => '500 agriculteurs formes aux techniques de compostage et rotation des cultures',
        ]);

        Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r1->id,
            'creator_user_id' => $admin->id,
            'responsible_user_id' => $admin->id,
            'description' => 'Organiser 10 sessions de formation en compostage',
            'start_date' => now()->subMonth(),
            'end_date' => now()->addMonths(3),
            'budget' => 2500000,
            'status' => ActivityStatus::ONGOING,
            'progress_percentage' => 40,
        ]);

        Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r1->id,
            'creator_user_id' => $admin->id,
            'description' => 'Distribuer les kits de demarrage (semences, outils)',
            'start_date' => now()->addMonth(),
            'end_date' => now()->addMonths(4),
            'budget' => 5000000,
            'status' => ActivityStatus::DRAFT,
            'progress_percentage' => 0,
        ]);

        $r2 = Result::create([
            'organization_id' => $org->id,
            'specific_objective_id' => $so1->id,
            'creator_user_id' => $admin->id,
            'description' => '20 parcelles de demonstration mises en place',
        ]);

        Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r2->id,
            'creator_user_id' => $admin->id,
            'description' => 'Identifier et preparer les parcelles de demonstration',
            'start_date' => now()->subMonths(2),
            'end_date' => now()->subWeek(),
            'budget' => 1000000,
            'status' => ActivityStatus::COMPLETED,
            'progress_percentage' => 100,
        ]);

        // Objectif Specifique 2
        $so2 = SpecificObjective::create([
            'organization_id' => $org->id,
            'logical_framework_id' => $lf->id,
            'creator_user_id' => $admin->id,
            'description' => 'Structurer 10 cooperatives agricoles pour la commercialisation groupee',
        ]);

        $r3 = Result::create([
            'organization_id' => $org->id,
            'specific_objective_id' => $so2->id,
            'creator_user_id' => $admin->id,
            'description' => '10 cooperatives legalement enregistrees et operationnelles',
        ]);

        Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r3->id,
            'creator_user_id' => $admin->id,
            'description' => 'Accompagner les demarches de formalisation des cooperatives',
            'start_date' => now()->addMonths(2),
            'end_date' => now()->addMonths(8),
            'budget' => 3000000,
            'status' => ActivityStatus::PENDING,
            'progress_percentage' => 0,
        ]);

        // Budgets
        Budget::create([
            'organization_id' => $org->id,
            'project_id' => $project->id,
            'creator_user_id' => $admin->id,
            'description' => 'Formation et ateliers',
            'quantity' => 10,
            'unit_cost' => 250000,
            'total_cost' => 2500000,
            'category' => 'formation',
        ]);

        Budget::create([
            'organization_id' => $org->id,
            'project_id' => $project->id,
            'creator_user_id' => $admin->id,
            'description' => 'Kits agricoles',
            'quantity' => 500,
            'unit_cost' => 10000,
            'total_cost' => 5000000,
            'category' => 'materiel',
        ]);

        Budget::create([
            'organization_id' => $org->id,
            'project_id' => $project->id,
            'creator_user_id' => $admin->id,
            'description' => 'Logistique et transport',
            'quantity' => 1,
            'unit_cost' => 3000000,
            'total_cost' => 3000000,
            'category' => 'logistique',
        ]);
    }
}
