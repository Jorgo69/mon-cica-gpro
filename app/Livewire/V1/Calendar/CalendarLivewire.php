<?php

namespace App\Livewire\V1\Calendar;

use App\Enums\ActivityStatus;
use App\Livewire\Traits\WithToastNotifications;
use App\Models\Activity;
use App\Models\Project;
use Carbon\Carbon;
use Livewire\Component;

class CalendarLivewire extends Component
{
    use WithToastNotifications;

    public string $viewMode = 'month'; // year, semester, quarter, month, week, day
    public string $currentDate;
    public ?string $projectFilter = null;
    public ?string $selectedActivityId = null;

    public function mount(): void
    {
        $this->currentDate = now()->format('Y-m-d');
    }

    public function navigate(string $direction): void
    {
        $date = Carbon::parse($this->currentDate);

        $this->currentDate = match ($this->viewMode) {
            'year' => ($direction === 'next' ? $date->addYear() : $date->subYear())->format('Y-m-d'),
            'semester' => ($direction === 'next' ? $date->addMonths(6) : $date->subMonths(6))->format('Y-m-d'),
            'quarter' => ($direction === 'next' ? $date->addMonths(3) : $date->subMonths(3))->format('Y-m-d'),
            'month' => ($direction === 'next' ? $date->addMonth() : $date->subMonth())->format('Y-m-d'),
            'week' => ($direction === 'next' ? $date->addWeek() : $date->subWeek())->format('Y-m-d'),
            'day' => ($direction === 'next' ? $date->addDay() : $date->subDay())->format('Y-m-d'),
        };
    }

    public function goToToday(): void
    {
        $this->currentDate = now()->format('Y-m-d');
    }

    public function setView(string $mode): void
    {
        if (in_array($mode, ['year', 'semester', 'quarter', 'month', 'week', 'day'])) {
            $this->viewMode = $mode;
        }
    }

    public function goToDay(string $date): void
    {
        $this->currentDate = $date;
        $this->viewMode = 'day';
    }

    public function selectActivity(string $id): void
    {
        $this->selectedActivityId = $id;
    }

    public function closeActivityModal(): void
    {
        $this->selectedActivityId = null;
    }

    public function exportIcal()
    {
        $events = $this->getEvents();

        $ical = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//CICA-GPRO//Calendar//FR\r\n";

        foreach ($events as $event) {
            $dtStart = Carbon::parse($event['start'])->format('Ymd');
            $dtEnd = Carbon::parse($event['end'] ?? $event['start'])->addDay()->format('Ymd');
            $summary = str_replace(["\r", "\n", ",", ";"], [' ', ' ', '\,', '\;'], $event['title']);
            $uid = $event['id'] . '@gpro';

            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= "UID:{$uid}\r\n";
            $ical .= "DTSTART;VALUE=DATE:{$dtStart}\r\n";
            $ical .= "DTEND;VALUE=DATE:{$dtEnd}\r\n";
            $ical .= "SUMMARY:{$summary}\r\n";
            if (!empty($event['description'])) {
                $desc = str_replace(["\r", "\n", ",", ";"], [' ', ' ', '\,', '\;'], $event['description']);
                $ical .= "DESCRIPTION:{$desc}\r\n";
            }
            $ical .= "END:VEVENT\r\n";
        }

        $ical .= "END:VCALENDAR\r\n";

        return response()->streamDownload(function () use ($ical) {
            echo $ical;
        }, 'calendrier-gpro.ics', [
            'Content-Type' => 'text/calendar',
        ]);
    }

    protected function getEvents(): array
    {
        $user = auth()->user();
        $orgId = $user->organization_id;

        $query = Activity::query()
            ->whereNotNull('start_date')
            ->with(['responsibleUser:id,name', 'result.specificObjective.logicalFramework.project:id,title']);

        if ($orgId) {
            $query->where('organization_id', $orgId);
        } else {
            $query->where('creator_user_id', $user->id);
        }

        if ($this->projectFilter) {
            $query->whereHas('result.specificObjective.logicalFramework', function ($q) {
                $q->where('project_id', $this->projectFilter);
            });
        }

        return $query->get()->map(function ($activity) {
            $project = $activity->result?->specificObjective?->logicalFramework?->project;
            return [
                'id' => $activity->id,
                'title' => $activity->description,
                'start' => $activity->start_date?->format('Y-m-d'),
                'end' => $activity->end_date?->format('Y-m-d'),
                'status' => $activity->status?->value,
                'statusKey' => $activity->status?->name,
                'color' => $activity->status?->hex() ?? '#94a3b8',
                'progress' => $activity->progress_percentage,
                'responsible' => $activity->responsibleUser?->name,
                'project' => $project?->title,
                'project_id' => $project?->id,
                'description' => strip_tags($activity->description ?? ''),
            ];
        })->toArray();
    }

    public function render()
    {
        $events = $this->getEvents();
        $date = Carbon::parse($this->currentDate);

        $projects = Project::query();
        $orgId = auth()->user()->organization_id;
        if ($orgId) {
            $projects->where('organization_id', $orgId);
        } else {
            $projects->where('creator_user_id', auth()->id());
        }

        $selectedActivity = $this->selectedActivityId
            ? Activity::with(['responsibleUser', 'result.specificObjective.logicalFramework.project'])->find($this->selectedActivityId)
            : null;

        return view('livewire.v1.calendar.calendar-livewire', [
            'events' => $events,
            'date' => $date,
            'projects' => $projects->select('id', 'title')->orderBy('title')->get(),
            'selectedActivity' => $selectedActivity,
        ]);
    }
}
