<?php

namespace App\Console\Commands;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Services\ReportService;
use Illuminate\Console\Command;

class GenerateReports extends Command
{
    protected $signature = 'gpro:generate-reports
                            {--format=pdf : Format de rapport (pdf ou docx)}
                            {--project= : ID specifique de projet (sinon tous les projets actifs)}';

    protected $description = 'Genere des rapports pour les projets actifs';

    public function handle(): int
    {
        $service = new ReportService();
        $format = $this->option('format');

        if ($projectId = $this->option('project')) {
            $project = Project::find($projectId);
            if (!$project) {
                $this->error("Projet non trouve : {$projectId}");
                return 1;
            }

            $this->generateOne($service, $project, $format);
            return 0;
        }

        $projects = Project::whereIn('status', [
            ProjectStatus::ACTIVE,
            ProjectStatus::ON_HOLD,
        ])->get();

        if ($projects->isEmpty()) {
            $this->info('Aucun projet actif a rapporter.');
            return 0;
        }

        $this->info("Generation de {$projects->count()} rapport(s) en {$format}...");

        foreach ($projects as $project) {
            $this->generateOne($service, $project, $format);
        }

        $this->info('Termine.');
        return 0;
    }

    protected function generateOne(ReportService $service, Project $project, string $format): void
    {
        try {
            $path = $service->generateAndNotify($project, $format);
            $this->info("  ✓ {$project->title} → {$path}");
        } catch (\Exception $e) {
            $this->error("  ✗ {$project->title} : {$e->getMessage()}");
        }
    }
}
