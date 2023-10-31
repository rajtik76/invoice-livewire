<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Report;
use App\Models\TaskHour;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        foreach (TaskHour::join('tasks', 'tasks.id', '=', 'task_hours.task_id')
                     ->select(['tasks.contract_id', 'task_hours.user_id'])
                     ->selectRaw('year(task_hours.date) as year')
                     ->selectRaw('month(task_hours.date) as month')
                     ->groupBy(['tasks.contract_id', 'task_hours.user_id'])
                     ->groupByRaw('year(task_hours.date)')
                     ->groupByRaw('month(task_hours.date)')
                     ->get() as $taskHour) {
            Report::factory()->create([
                'user_id' => $taskHour->user_id,
                'contract_id' => $taskHour->contract_id,
                'year' => $taskHour->year,
                'month' => $taskHour->month,
            ]);
        }
    }
}
