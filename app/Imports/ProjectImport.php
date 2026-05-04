<?php

namespace App\Imports;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProjectImport
{
    protected array $errors = [];
    protected int $imported = 0;

    public function __construct(
        protected string $organizationId,
        protected string $creatorUserId,
    ) {}

    public function preview(Collection $rows): array
    {
        $preview = [];
        $this->errors = [];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +1 for header, +1 for 1-based
            $data = $this->mapRow($row);

            if (!$data) {
                $this->errors[] = ['row' => $rowNum, 'message' => __('import.empty_row')];
                continue;
            }

            $validation = $this->validateRow($data, $rowNum);
            $preview[] = array_merge($data, [
                'row' => $rowNum,
                'valid' => empty($validation),
                'errors' => $validation,
            ]);
        }

        return $preview;
    }

    public function import(Collection $rows): array
    {
        $this->errors = [];
        $this->imported = 0;

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;
            $data = $this->mapRow($row);

            if (!$data) continue;

            $validation = $this->validateRow($data, $rowNum);
            if (!empty($validation)) {
                $this->errors = array_merge($this->errors, $validation);
                continue;
            }

            $status = $this->resolveStatus($data['status'] ?? '');

            Project::create([
                'title' => $data['title'],
                'project_code' => $data['code'] ?: $this->generateCode(),
                'description' => $data['description'] ?? null,
                'status' => $status,
                'start_date' => $this->parseDate($data['start_date']),
                'end_date' => $this->parseDate($data['end_date']),
                'currency' => $data['currency'] ?? 'XOF',
                'organization_id' => $this->organizationId,
                'creator_user_id' => $this->creatorUserId,
            ]);

            $this->imported++;
        }

        return [
            'imported' => $this->imported,
            'errors' => $this->errors,
        ];
    }

    protected function mapRow($row): ?array
    {
        $values = is_array($row) ? array_values($row) : $row->toArray();

        // Skip completely empty rows
        if (empty(array_filter($values, fn ($v) => $v !== null && $v !== ''))) {
            return null;
        }

        return [
            'title' => trim($values[0] ?? ''),
            'code' => trim($values[1] ?? ''),
            'description' => trim($values[2] ?? ''),
            'status' => trim($values[3] ?? ''),
            'start_date' => trim($values[4] ?? ''),
            'end_date' => trim($values[5] ?? ''),
            'currency' => strtoupper(trim($values[6] ?? 'XOF')),
        ];
    }

    protected function validateRow(array $data, int $rowNum): array
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors[] = ['row' => $rowNum, 'message' => __('import.title_required')];
        }

        if ($data['start_date'] && !$this->parseDate($data['start_date'])) {
            $errors[] = ['row' => $rowNum, 'message' => __('import.invalid_date', ['field' => __('common.start_date')])];
        }

        if ($data['end_date'] && !$this->parseDate($data['end_date'])) {
            $errors[] = ['row' => $rowNum, 'message' => __('import.invalid_date', ['field' => __('common.end_date')])];
        }

        return $errors;
    }

    protected function resolveStatus(string $status): ProjectStatus
    {
        $lower = mb_strtolower(trim($status));

        foreach (ProjectStatus::cases() as $case) {
            if (mb_strtolower($case->value) === $lower || mb_strtolower($case->name) === $lower) {
                return $case;
            }
        }

        return ProjectStatus::DRAFT;
    }

    protected function parseDate(string $date): ?string
    {
        if (empty($date)) return null;

        $formats = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'm/d/Y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            } catch (\Exception) {
                continue;
            }
        }

        return null;
    }

    protected function generateCode(): string
    {
        return 'IMP-' . strtoupper(Str::random(6));
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
