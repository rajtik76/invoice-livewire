<?php

namespace App\Livewire\Table;

use App\Models\Contract;
use App\Models\Task;
use App\Traits\HasActiveActions;
use App\Traits\HasActiveFilter;
use Illuminate\Database\Eloquent\Builder;
use RamonRietdijk\LivewireTables\Columns\BaseColumn;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;
use RamonRietdijk\LivewireTables\Enums\Direction;

class TaskTable extends BaseTable
{
    use HasActiveActions, HasActiveFilter;

    protected string $model = Task::class;

    /** @return BaseColumn[] */
    protected function baseColumns(): array
    {
        return [
            ViewColumn::make('Active', 'components.table-state'),
            Column::make('Contract', 'contract_id')
                ->displayUsing(function (mixed $value, Task $model): string {
                    return $model->load('contract')->contract->name;
                })
                ->sortable(function (Builder $builder, Direction $direction) {
                    $builder->orderBy(
                        Contract::select('id')
                            ->whereColumn('contracts.id', 'tasks.contract_id'), $direction->value);
                })
                ->searchable(function (Builder $builder, mixed $search): void {
                    $builder->whereIn('contract_id',
                        Contract::currentUser()
                            ->where('name', 'like', "%{$search}%")
                            ->pluck('id')
                    );
                }),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable()
                ->displayUsing(function (mixed $value, Task $model): string {
                    $output = $model->name;
                    if ($model->url) {
                        $output = "<a href='{$model->url}' class='underline text-blue-400' target='_blank'>{$model->name}</a>";
                    }

                    return $output;
                })
                ->asHtml()
                ->clickable(false),
            ViewColumn::make('Hours', 'components.table-task-hours-button')
                ->sortable(function (Builder $builder, Direction $direction) {
                    $builder->withSum('taskHour', 'hours')->orderBy('task_hour_sum_hours', $direction->value);
                })
                ->clickable(false),
        ];
    }
}
