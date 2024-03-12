<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskHour;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        // get task hours for current month
        $taskHours = TaskHour::currentUser()->whereBetween('date', [Carbon::parse('midnight first day of this month'), Carbon::parse('midnight last day of this month')]);

        // get invoice amount for current month
        $amount = $taskHours
            ->get()
            ->groupBy('task.contract.currency')
            ->map(
                fn (Collection $items) => $items->sum(
                    fn (TaskHour $taskHour) => $taskHour->task->contract->price_per_hour * $taskHour->hours
                )
            );

        $topThreeTaskCount = 3;

        return view('dashboard', [
            'tasks' => Task::currentUser()
                ->active()
                ->count(),
            'hours' => $taskHours->sum('hours'),
            'amount' => $amount,
            'topThreeTask' => Task::with(['contract.customer'])
                ->where('active', true)
                ->withSum(['taskHours' => fn (Builder $builder) => $builder->whereBetween('date', [Carbon::parse('midnight first day of this month'), Carbon::parse('midnight last day of this month')])], 'hours')
                ->orderByDesc('task_hours_sum_hours')
                ->having('task_hours_sum_hours', '>', 0)
                ->limit($topThreeTaskCount)
                ->get(),
            'topThreeTaskCount' => $topThreeTaskCount,
        ]
        );
    }
}
