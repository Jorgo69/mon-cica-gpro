<?php

namespace Database\Seeders;

use App\Enums\ActivityStatus;
use App\Enums\Currency;
use App\Enums\ProjectStatus;
use App\Models\Activity;
use App\Models\Budget;
use App\Models\Comment;
use App\Models\Expense;
use App\Models\Indicator;
use App\Models\IndicatorMeasurement;
use App\Models\LogicalFramework;
use App\Models\Organization;
use App\Models\Project;
use App\Models\ProgressTracker;
use App\Models\Resource;
use App\Models\Result;
use App\Models\ShareToken;
use App\Models\SpecificObjective;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoProjectSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedProjexiaProjects();
        $this->seedEspoirProject();
    }

    private function seedProjexiaProjects(): void
    {
        $org = Organization::where('slug', 'projexia-international')->first();
        if (! $org) return;

        $admin = User::where('email', 'admin@projexia.org')->first();
        $manager = User::where('email', 'manager@projexia.org')->first();
        $member = User::where('email', 'membre@projexia.org')->first();
        if (! $admin) return;

        // ═══════════════════════════════════════════════════════════
        // PROJET 1 : Formation agricole (template + actif)
        // ═══════════════════════════════════════════════════════════
        $p1 = Project::create([
            'organization_id' => $org->id,
            'creator_user_id' => $admin->id,
            'project_code' => 'DEMO-2026-001',
            'title' => 'Formation des agriculteurs en techniques durables',
            'short_title' => 'AgriDurable',
            'description' => 'Programme de formation pour 500 agriculteurs dans la region du Zou sur les techniques agricoles durables et la gestion des ressources naturelles. Finance par le Fonds Vert pour le Climat.',
            'status' => ProjectStatus::ACTIVE,
            'currency' => Currency::XOF,
            'start_date' => now()->subMonths(3),
            'end_date' => now()->addMonths(9),
            'is_template' => true,
        ]);

        // Assigner les membres au projet (syncWithoutDetaching car creator auto-assigné au boot)
        $p1->members()->syncWithoutDetaching(array_filter([$admin->id, $manager?->id, $member?->id]));

        $lf1 = LogicalFramework::create([
            'organization_id' => $org->id,
            'project_id' => $p1->id,
            'creator_user_id' => $admin->id,
            'general_objective' => 'Ameliorer la securite alimentaire et les revenus de 500 familles agricoles du Zou d\'ici fin 2027',
        ]);

        // --- OS1 : Capacites techniques ---
        $so1 = SpecificObjective::create([
            'organization_id' => $org->id,
            'logical_framework_id' => $lf1->id,
            'creator_user_id' => $admin->id,
            'description' => 'Renforcer les capacites techniques de 500 agriculteurs en pratiques agricoles durables',
        ]);

        Indicator::create([
            'indicatorable_type' => SpecificObjective::class,
            'indicatorable_id' => $so1->id,
            'description' => 'Nombre d\'agriculteurs formes',
            'unit' => 'personnes',
            'baseline_value' => '0',
            'target_value' => '500',
            'current_value' => '180',
        ]);

        $r1 = Result::create([
            'organization_id' => $org->id,
            'specific_objective_id' => $so1->id,
            'creator_user_id' => $admin->id,
            'description' => '500 agriculteurs formes aux techniques de compostage et rotation des cultures',
        ]);

        $a1 = Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r1->id,
            'creator_user_id' => $admin->id,
            'responsible_user_id' => $manager?->id ?? $admin->id,
            'description' => 'Organiser 10 sessions de formation en compostage',
            'start_date' => now()->subMonths(2),
            'end_date' => now()->addMonths(2),
            'budget' => 2500000,
            'status' => ActivityStatus::ONGOING,
            'progress_percentage' => 60,
        ]);

        // Progression historique
        ProgressTracker::create([
            'activity_id' => $a1->id,
            'creator_user_id' => $admin->id,
            'progress_percentage' => 20,
            'justification' => '2 sessions realisees sur 10. Bonne participation des agriculteurs.',
            'date' => now()->subWeeks(6)->toDateString(),
            'created_at' => now()->subWeeks(6),
        ]);
        ProgressTracker::create([
            'activity_id' => $a1->id,
            'creator_user_id' => $manager?->id ?? $admin->id,
            'progress_percentage' => 60,
            'justification' => '6 sessions realisees. 180 agriculteurs formes. Les retours sont positifs.',
            'date' => now()->subWeek()->toDateString(),
            'created_at' => now()->subWeek(),
        ]);

        $a2 = Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r1->id,
            'creator_user_id' => $admin->id,
            'responsible_user_id' => $member?->id ?? $admin->id,
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
            'description' => '20 parcelles de demonstration mises en place et fonctionnelles',
        ]);

        Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r2->id,
            'creator_user_id' => $admin->id,
            'responsible_user_id' => $admin->id,
            'description' => 'Identifier et preparer les parcelles de demonstration',
            'start_date' => now()->subMonths(3),
            'end_date' => now()->subWeeks(2),
            'budget' => 1000000,
            'status' => ActivityStatus::COMPLETED,
            'progress_percentage' => 100,
        ]);

        // --- OS2 : Cooperatives ---
        $so2 = SpecificObjective::create([
            'organization_id' => $org->id,
            'logical_framework_id' => $lf1->id,
            'creator_user_id' => $admin->id,
            'description' => 'Structurer 10 cooperatives agricoles pour la commercialisation groupee',
        ]);

        Indicator::create([
            'indicatorable_type' => SpecificObjective::class,
            'indicatorable_id' => $so2->id,
            'description' => 'Cooperatives enregistrees',
            'unit' => 'cooperatives',
            'baseline_value' => '0',
            'target_value' => '10',
            'current_value' => '3',
        ]);

        $r3 = Result::create([
            'organization_id' => $org->id,
            'specific_objective_id' => $so2->id,
            'creator_user_id' => $admin->id,
            'description' => '10 cooperatives legalement enregistrees et operationnelles',
        ]);

        $a4 = Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r3->id,
            'creator_user_id' => $admin->id,
            'responsible_user_id' => $manager?->id ?? $admin->id,
            'description' => 'Accompagner les demarches de formalisation des cooperatives',
            'start_date' => now()->subMonth(),
            'end_date' => now()->addMonths(6),
            'budget' => 3000000,
            'status' => ActivityStatus::ONGOING,
            'progress_percentage' => 30,
        ]);

        // En retard volontairement pour tester les alertes
        Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r3->id,
            'creator_user_id' => $admin->id,
            'responsible_user_id' => $member?->id ?? $admin->id,
            'description' => 'Former les leaders des cooperatives en gestion comptable',
            'start_date' => now()->subMonths(2),
            'end_date' => now()->subWeek(),
            'budget' => 1500000,
            'status' => ActivityStatus::ONGOING,
            'progress_percentage' => 50,
        ]);

        // --- Budgets ---
        $b1 = Budget::create([
            'organization_id' => $org->id,
            'project_id' => $p1->id,
            'creator_user_id' => $admin->id,
            'description' => 'Formation et ateliers',
            'quantity' => 10,
            'unit_cost' => 250000,
            'total_cost' => 2500000,
            'category' => 'formation',
        ]);

        $b2 = Budget::create([
            'organization_id' => $org->id,
            'project_id' => $p1->id,
            'creator_user_id' => $admin->id,
            'description' => 'Kits agricoles (semences, outils)',
            'quantity' => 500,
            'unit_cost' => 10000,
            'total_cost' => 5000000,
            'category' => 'materiel',
        ]);

        $b3 = Budget::create([
            'organization_id' => $org->id,
            'project_id' => $p1->id,
            'creator_user_id' => $admin->id,
            'description' => 'Logistique et transport',
            'quantity' => 1,
            'unit_cost' => 3000000,
            'total_cost' => 3000000,
            'category' => 'logistique',
        ]);

        Budget::create([
            'organization_id' => $org->id,
            'project_id' => $p1->id,
            'creator_user_id' => $admin->id,
            'description' => 'Frais de formalisation cooperatives',
            'quantity' => 10,
            'unit_cost' => 150000,
            'total_cost' => 1500000,
            'category' => 'administratif',
        ]);

        // --- Depenses reelles ---
        Expense::create([
            'organization_id' => $org->id,
            'project_id' => $p1->id,
            'budget_id' => $b1->id,
            'creator_user_id' => $admin->id,
            'description' => 'Sessions 1-4 : location salles + formateurs',
            'amount' => 950000,
            'expense_date' => now()->subWeeks(5),
            'category' => 'formation',
        ]);

        Expense::create([
            'organization_id' => $org->id,
            'project_id' => $p1->id,
            'budget_id' => $b1->id,
            'creator_user_id' => $admin->id,
            'description' => 'Sessions 5-6 : formateurs + repas participants',
            'amount' => 620000,
            'expense_date' => now()->subWeeks(2),
            'category' => 'formation',
        ]);

        Expense::create([
            'organization_id' => $org->id,
            'project_id' => $p1->id,
            'budget_id' => $b3->id,
            'creator_user_id' => $manager?->id ?? $admin->id,
            'description' => 'Carburant + location vehicules janvier-mars',
            'amount' => 780000,
            'expense_date' => now()->subMonth(),
            'category' => 'logistique',
        ]);

        // --- Mesures d'indicateurs (tendances) ---
        $ind1 = Indicator::where('indicatorable_id', $so1->id)->first();
        if ($ind1) {
            foreach ([
                ['value' => 0, 'measured_at' => now()->subMonths(3), 'comment' => 'Debut du projet'],
                ['value' => 50, 'measured_at' => now()->subMonths(2), 'comment' => '2 premieres sessions realisees'],
                ['value' => 120, 'measured_at' => now()->subMonth(), 'comment' => '4 sessions terminees, bon rythme'],
                ['value' => 180, 'measured_at' => now()->subWeek(), 'comment' => '6 sessions, objectif mi-parcours atteint'],
            ] as $m) {
                IndicatorMeasurement::create([
                    'indicator_id' => $ind1->id,
                    'value' => $m['value'],
                    'comment' => $m['comment'],
                    'measured_at' => $m['measured_at'],
                    'measured_by' => $admin->id,
                ]);
            }
        }

        // --- Commentaires sur activites ---
        Comment::create([
            'commentable_type' => Activity::class,
            'commentable_id' => $a1->id,
            'user_id' => $admin->id,
            'body' => 'Les 2 premieres sessions ont eu un bon taux de participation. Les agriculteurs sont motives.',
            'created_at' => now()->subWeeks(5),
        ]);

        if ($manager) {
            Comment::create([
                'commentable_type' => Activity::class,
                'commentable_id' => $a1->id,
                'user_id' => $manager->id,
                'body' => 'On a un probleme de transport pour la session 7 dans le village de Zagnanado. @admin peut-on louer un vehicule supplementaire ?',
                'created_at' => now()->subDays(3),
            ]);
        }

        // --- Lien de partage public (bailleur) ---
        ShareToken::create([
            'project_id' => $p1->id,
            'token' => Str::random(48),
            'label' => 'Fonds Vert pour le Climat - Suivi bailleur',
            'expires_at' => now()->addMonths(6),
            'is_active' => true,
            'view_count' => 12,
        ]);

        // --- Ressources (liees aux activites) ---
        Resource::create([
            'organization_id' => $org->id,
            'activity_id' => $a1->id,
            'creator_user_id' => $admin->id,
            'name' => 'Formateur principal agronomie',
            'type' => 'humaine',
            'quantity' => 1,
            'unit_cost' => 150000,
        ]);

        Resource::create([
            'organization_id' => $org->id,
            'activity_id' => $a4->id,
            'creator_user_id' => $admin->id,
            'name' => 'Vehicule terrain (location)',
            'type' => 'materielle',
            'quantity' => 2,
            'unit_cost' => 75000,
        ]);

        // ═══════════════════════════════════════════════════════════
        // PROJET 2 : Eau potable (en brouillon, EUR)
        // ═══════════════════════════════════════════════════════════
        $p2 = Project::create([
            'organization_id' => $org->id,
            'creator_user_id' => $admin->id,
            'project_code' => 'DEMO-2026-002',
            'title' => 'Acces a l\'eau potable dans 15 villages du Borgou',
            'short_title' => 'EauBorgou',
            'description' => 'Construction et rehabilitation de 30 points d\'eau dans la region du Borgou. Partenariat avec WaterAid et la commune de Parakou.',
            'status' => ProjectStatus::SUBMITTED,
            'currency' => Currency::EUR,
            'start_date' => now()->addMonth(),
            'end_date' => now()->addMonths(18),
        ]);

        $p2->members()->syncWithoutDetaching(array_filter([$admin->id, $manager?->id]));

        $lf2 = LogicalFramework::create([
            'organization_id' => $org->id,
            'project_id' => $p2->id,
            'creator_user_id' => $admin->id,
            'general_objective' => 'Garantir l\'acces a l\'eau potable pour 15 000 habitants de 15 villages du Borgou',
        ]);

        $so3 = SpecificObjective::create([
            'organization_id' => $org->id,
            'logical_framework_id' => $lf2->id,
            'creator_user_id' => $admin->id,
            'description' => 'Construire et rehabiliter 30 points d\'eau fonctionnels',
        ]);

        $r4 = Result::create([
            'organization_id' => $org->id,
            'specific_objective_id' => $so3->id,
            'creator_user_id' => $admin->id,
            'description' => '30 forages equipes de pompes manuelles ou solaires',
        ]);

        Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r4->id,
            'creator_user_id' => $admin->id,
            'description' => 'Etude hydrogeologique des 15 villages',
            'start_date' => now()->addMonth(),
            'end_date' => now()->addMonths(3),
            'budget' => 45000,
            'status' => ActivityStatus::PENDING,
        ]);

        Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r4->id,
            'creator_user_id' => $admin->id,
            'description' => 'Forage et installation des pompes',
            'start_date' => now()->addMonths(3),
            'end_date' => now()->addMonths(12),
            'budget' => 280000,
            'status' => ActivityStatus::PENDING,
        ]);

        Budget::create([
            'organization_id' => $org->id,
            'project_id' => $p2->id,
            'creator_user_id' => $admin->id,
            'description' => 'Etudes techniques',
            'quantity' => 15,
            'unit_cost' => 3000,
            'total_cost' => 45000,
            'category' => 'technique',
        ]);

        Budget::create([
            'organization_id' => $org->id,
            'project_id' => $p2->id,
            'creator_user_id' => $admin->id,
            'description' => 'Construction forages',
            'quantity' => 30,
            'unit_cost' => 9300,
            'total_cost' => 280000,
            'category' => 'infrastructure',
        ]);
    }

    private function seedEspoirProject(): void
    {
        $org = Organization::where('slug', 'ong-espoir')->first();
        if (! $org) return;

        $admin = User::where('email', 'admin@ong-espoir.org')->first();
        if (! $admin) return;

        // ═══════════════════════════════════════════════════════════
        // PROJET 3 : Alphabetisation (org 2, pour tester isolation)
        // ═══════════════════════════════════════════════════════════
        $p3 = Project::create([
            'organization_id' => $org->id,
            'creator_user_id' => $admin->id,
            'project_code' => 'ESP-2026-001',
            'title' => 'Programme d\'alphabetisation des femmes du Mono',
            'short_title' => 'AlphaFemmes',
            'description' => 'Formation en lecture, ecriture et calcul pour 300 femmes dans 8 communes du departement du Mono. Partenariat avec l\'UNICEF.',
            'status' => ProjectStatus::ACTIVE,
            'currency' => Currency::XOF,
            'start_date' => now()->subMonth(),
            'end_date' => now()->addMonths(11),
        ]);

        $p3->members()->syncWithoutDetaching([$admin->id]);

        $lf3 = LogicalFramework::create([
            'organization_id' => $org->id,
            'project_id' => $p3->id,
            'creator_user_id' => $admin->id,
            'general_objective' => 'Reduire le taux d\'analphabetisme feminin de 40% a 20% dans le Mono d\'ici 2027',
        ]);

        $so4 = SpecificObjective::create([
            'organization_id' => $org->id,
            'logical_framework_id' => $lf3->id,
            'creator_user_id' => $admin->id,
            'description' => 'Former 300 femmes en lecture, ecriture et calcul de base',
        ]);

        Indicator::create([
            'indicatorable_type' => SpecificObjective::class,
            'indicatorable_id' => $so4->id,
            'description' => 'Femmes alphabetisees',
            'unit' => 'personnes',
            'baseline_value' => '0',
            'target_value' => '300',
            'current_value' => '45',
        ]);

        $r5 = Result::create([
            'organization_id' => $org->id,
            'specific_objective_id' => $so4->id,
            'creator_user_id' => $admin->id,
            'description' => '300 femmes capables de lire et ecrire un texte simple',
        ]);

        Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r5->id,
            'creator_user_id' => $admin->id,
            'responsible_user_id' => $admin->id,
            'description' => 'Recruter et former 16 animatrices locales',
            'start_date' => now()->subMonth(),
            'end_date' => now()->addWeeks(2),
            'budget' => 800000,
            'status' => ActivityStatus::ONGOING,
            'progress_percentage' => 75,
        ]);

        Activity::create([
            'organization_id' => $org->id,
            'result_id' => $r5->id,
            'creator_user_id' => $admin->id,
            'description' => 'Conduire les cours d\'alphabetisation (6 mois)',
            'start_date' => now()->addWeeks(3),
            'end_date' => now()->addMonths(9),
            'budget' => 3600000,
            'status' => ActivityStatus::PENDING,
        ]);

        Budget::create([
            'organization_id' => $org->id,
            'project_id' => $p3->id,
            'creator_user_id' => $admin->id,
            'description' => 'Formation animatrices',
            'quantity' => 16,
            'unit_cost' => 50000,
            'total_cost' => 800000,
            'category' => 'formation',
        ]);

        Budget::create([
            'organization_id' => $org->id,
            'project_id' => $p3->id,
            'creator_user_id' => $admin->id,
            'description' => 'Materiel pedagogique et fournitures',
            'quantity' => 300,
            'unit_cost' => 5000,
            'total_cost' => 1500000,
            'category' => 'materiel',
        ]);
    }
}
