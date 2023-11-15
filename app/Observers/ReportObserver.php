<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Report;
use App\Models\TaskHour;

class ReportObserver
{
    public function created(Report $report): void
    {
        $this->updateContent($report);
    }

    public function updated(Report $report): void
    {
        if ($report->isDirty(['contract_id', 'year', 'month'])) {
            $this->updateContent($report);
        }
    }

    public function deleted(Report $report): void
    {
    }

    public function restored(Report $report): void
    {
    }

    public function forceDeleted(Report $report): void
    {
    }

    private function updateContent(Report $report): void
    {
        $report->updateQuietly([
            'content' => TaskHour::with('task')
                ->whereIn('task_id', $report->contract->tasks()->pluck('id'))
                ->whereYear('date', strval($report->year))
                ->whereMonth('date', strval($report->month))
                ->orderBy('date')
                ->get()
                ->map(fn (TaskHour $taskHour) => [
                    'name' => $taskHour->task->name,
                    'url' => $taskHour->task->url,
                    'date' => $taskHour->date->toDateString(),
                    'hours' => $taskHour->hours,
                ])
                ->groupBy('date')
                ->toArray(),
        ]);
    }
}
