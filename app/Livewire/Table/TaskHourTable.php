<?php

namespace App\Livewire\Table;

use App\Models\Task;
use App\Models\TaskHour;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use RamonRietdijk\LivewireTables\Columns\BaseColumn;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Enums\Direction;
use RamonRietdijk\LivewireTables\Filters\BaseFilter;
use RamonRietdijk\LivewireTables\Filters\SelectFilter;
use SebastianBergmann\CodeCoverage\Filter;

class TaskHourTable extends BaseTable
{
    protected string $model = TaskHour::class;

    /** @return BaseColumn[] */
    protected function baseColumns(): array
    {
        return [
            Column::make('Date', 'date')
                ->sortable()
                ->searchable()
                ->displayUsing(function (Carbon $value): string {
                    return $value->toDateString();
                }),
            Column::make('Task', 'task_id')
                ->displayUsing(function (mixed $value, TaskHour $model): string {
                    return $model->load('task')->task->name;
                })
                ->sortable(function (Builder $builder, Direction $direction) {
                    $builder->orderBy(
                        Task::select('id')
                            ->whereColumn('tasks.id', 'task_hours.task_id'), $direction->value);
                })
                ->searchable(function (Builder $builder, mixed $search): void {
                    $builder->whereIn('task_id',
                        Task::currentUser()
                            ->where('name', 'like', "%{$search}%")
                            ->pluck('id')
                    );
                }),
            Column::make('Hours', 'hours')
                ->sortable()
                ->searchable(),
        ];
    }

    /** @return BaseFilter[] */
    protected function baseFilters(): array
    {
        return [
            SelectFilter::make('Task', 'task_id')
                ->options(Task::getOptions()),
        ];
    }
}
