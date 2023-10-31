<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Contract;
use App\Models\Task;

readonly class InvoiceComputedData
{
    public function __construct(
        private int $contractId,
        private int $year,
        private int $month)
    {
    }

    /**
     * Calculate invoice month work hours content
     *
     * @return array<int, array{name: string, url: string, hours: string}>
     */
    public function getContent(): array
    {
        return Task::with(['taskHours' => fn ($query) => $query->whereYear('date', $this->year)->whereMonth('date', $this->month)])
            ->where('contract_id', $this->contractId)
            ->orderBy('name')
            ->get()
            // filter only task with hours
            ->filter(fn (Task $task) => $task->taskHours->sum('hours'))
            // key by task id
            ->keyBy('id')
            // and transform array to desired output
            ->map(fn (Task $task) => [
                'name' => $task->name,
                'url' => $task->url,
                'hours' => $task->taskHours->sum('hours'),
            ])
            ->toArray();
    }

    /**
     * Calculate invoice total amount
     */
    public function getTotalAmount(): float
    {
        return collect($this->getContent())->sum('hours') * Contract::find($this->contractId)->price_per_hour;
    }
}
