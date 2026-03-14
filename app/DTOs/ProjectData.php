<?php

namespace App\DTOs;

class ProjectData
{
    public function __construct(
        public readonly string $projectName,
        public readonly string $projectTypeId,
        public readonly ?array $generalObjectives = null,
        public readonly ?string $amount = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?string $duration = null,
        public readonly ?string $description = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            projectName: $data['project_name'],
            projectTypeId: $data['project_type_id'],
            generalObjectives: $data['general_objectives'] ?? null,
            amount: $data['amount'] ?? null,
            startDate: $data['start_date'] ?? null,
            endDate: $data['end_date'] ?? null,
            duration: $data['duration'] ?? null,
            description: $data['description'] ?? null,
        );
    }
}
