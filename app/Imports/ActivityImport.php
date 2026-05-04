<?php

namespace App\Imports;

use App\Enums\ActivityStatus;
use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ActivityImport
{
    protected array $errors = [];
    protected int $imported = 0;

    public function __construct(
        protected string $resultId,
        protected string $organizationId,
        protected string $creatorUserId,
    ) {}

    public function preview(Collection $rows): array
    {
        $preview = [];
        $this->errors = [];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;
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

            $responsibleId = $this->resolveUser($data['responsible_email']);
            $status = $this->resolveStatus($data['status'] ?? '');

            Activity::create([
                'description' => $data['description'],
                'responsible_user_id' => $responsibleId,
                'status' => $status,
                'start_date' => $this->parseDate($data['start_date']),
                'end_date' => $this->parseDate($data['end_date']),
                'budget' => (float) ($data['budget'] ?? 0),
                'progress_percentage' => min(100, max(0, (int) ($data['progress'] ?? 0))),
                'result_id' => $this->resultId,
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

        if (empty(array_filter($values, fn ($v) => $v !== null && $v !== ''))) {
            return null;
        }

        return [
            'description' => trim($values[0] ?? ''),
            'responsible_email' => trim($values[1] ?? ''),
            'status' => trim($values[2] ?? ''),
            'start_date' => trim($values[3] ?? ''),
            'end_date' => trim($values[4] ?? ''),
            'budget' => trim($values[5] ?? '0'),
            'progress' => trim($values[6] ?? '0'),
        ];
    }

    protected function validateRow(array $data, int $rowNum): array
    {
        $errors = [];

        if (empty($data['description'])) {
            $errors[] = ['row' => $rowNum, 'message' => __('import.description_required')];
        }

        if ($data['start_date'] && !$this->parseDate($data['start_date'])) {
            $errors[] = ['row' => $rowNum, 'message' => __('import.invalid_date', ['field' => __('common.start_date')])];
        }

        if ($data['end_date'] && !$this->parseDate($data['end_date'])) {
            $errors[] = ['row' => $rowNum, 'message' => __('import.invalid_date', ['field' => __('common.end_date')])];
        }

        if ($data['budget'] && !is_numeric($data['budget'])) {
            $errors[] = ['row' => $rowNum, 'message' => __('import.invalid_number', ['field' => __('budgets.budget')])];
        }

        return $errors;
    }

    protected function resolveUser(string $email): ?string
    {
        if (empty($email)) return null;

        return User::where('email', $email)
            ->where('organization_id', $this->organizationId)
            ->value('id');
    }

    protected function resolveStatus(string $status): ActivityStatus
    {
        $lower = mb_strtolower(trim($status));

        foreach (ActivityStatus::cases() as $case) {
            if (mb_strtolower($case->value) === $lower || mb_strtolower($case->name) === $lower) {
                return $case;
            }
        }

        return ActivityStatus::PENDING;
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

    public function getErrors(): array
    {
        return $this->errors;
    }
}
