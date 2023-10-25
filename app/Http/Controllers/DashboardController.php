<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskHour;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        // get task hours for current month
        $taskHours = TaskHour::currentUser()->whereBetween('date', [Carbon::parse('midnight first day of this month'), now()]);

        // get invoice amount for current month
        $amount = $taskHours
            ->get()
            ->groupBy('task.contract.currency')
            ->map(
                fn(Collection $items) => $items->sum(
                    fn(TaskHour $taskHour) => $taskHour->task->contract->price_per_hour * $taskHour->hours
                )
            );

        return view('dashboard', [
                'tasks' => Task::currentUser()
                    ->active()
                    ->count(),
                'hours' => $taskHours->sum('hours'),
                'amount' => $amount
            ]
        );
    }
}
