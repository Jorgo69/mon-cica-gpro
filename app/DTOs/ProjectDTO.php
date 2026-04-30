<?php

namespace App\DTOs;

class ProjectDTO
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $projectTypeId = null,
        public readonly ?string $projectCode = null,
        public readonly ?string $shortTitle = null,
        public readonly ?string $status = null,
        public readonly ?array $generalObjectives = null,
        public readonly ?string $description = null,
        public readonly ?string $problemAnalysis = null,
        public readonly ?string $strategy = null,
        public readonly ?string $justification = null,
        public readonly ?string $amount = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?string $duration = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? $data['project_name'] ?? '',
            projectTypeId: $data['project_type_id'] ?: null,
            projectCode: $data['project_code'] ?? null,
            shortTitle: $data['short_title'] ?? null,
            status: $data['status'] ?? null,
            generalObjectives: $data['general_objectives'] ?? null,
            description: $data['description'] ?? null,
            problemAnalysis: $data['problem_analysis'] ?? null,
            strategy: $data['strategy'] ?? null,
            justification: $data['justification'] ?? null,
            amount: $data['amount'] ?? null,
            startDate: $data['start_date'] ?? null,
            endDate: $data['end_date'] ?? null,
            duration: $data['duration'] ?? null,
        );
    }
}
