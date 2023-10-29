<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Task;
use App\Models\TaskHour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        TaskHour::all()
            // I need only task_id + year + month
            ->map(fn (TaskHour $taskHour) => [
                'task_id' => $taskHour->task_id,
                'year' => sprintf('%04d', $taskHour->date->year),
                'month' => sprintf('%02d', $taskHour->date->month),
            ])
            // group by by task + year + month
            ->groupBy(fn ($item) => $item['task_id'].$item['year'].$item['month'])
            ->each(function (Collection $item) {
                // take first item
                $firstItem = $item->first();

                // and create invoice model
                Invoice::factory()->create([
                    'contract_id' => Task::find($firstItem['task_id'])->contract->id,
                    'year' => $firstItem['year'],
                    'month' => $firstItem['month'],
                ]);
            });
    }
}
