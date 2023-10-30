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
        TaskHour::with('task')->get()
            // I need only task + year + month
            ->map(fn (TaskHour $taskHour) => [
                'task' => $taskHour->task,
                'year' => sprintf('%04d', $taskHour->date->year),
                'month' => sprintf('%02d', $taskHour->date->month),
            ])
            // group by by task + year + month
            ->groupBy(fn ($item) => "{$item['task']->id}{$item['year']}{$item['month']}")
            ->each(function (Collection $item) {
                // take first item
                $firstItem = $item->first();

                // and create invoice model
                Invoice::factory()->create([
                    'user_id' => $firstItem['task']->user_id,
                    'contract_id' => $firstItem['task']->contract->id,
                    'year' => $firstItem['year'],
                    'month' => $firstItem['month'],
                ]);
            });
    }
}
