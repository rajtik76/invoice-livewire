<?php

declare(strict_types=1);

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

    public function redirectToTaskHour(string $task): void
    {
        $this->redirect(route('table.taskHour', intval($task)), true);
    }

    /** @return BaseColumn[] */
    protected function baseColumns(): array
    {
        return [
            ViewColumn::make(__('base.active'), 'components.table-state'),
            Column::make(__('base.contract'), 'contract_id')
                ->displayUsing(function (mixed $value, Task $model): string {
                    return $model->load('contract')->contract->name;
                })
                ->sortable(function (Builder $builder, Direction $direction) {
                    /** @var Builder $contracts */
                    $contracts = Contract::select('id')->whereColumn('contracts.id', 'tasks.contract_id');
                    $builder->orderBy($contracts, $direction->value);
                })
                ->searchable(function (Builder $builder, mixed $search): void {
                    $builder->whereIn('contract_id',
                        Contract::currentUser()
                            ->where('name', 'like', "%{$search}%")
                            ->pluck('id')
                    );
                }),
            Column::make(__('base.name'), 'name')
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
            ViewColumn::make(__('base.hours'), 'components.table-task-hours-button')
                ->sortable(function (Builder $builder, Direction $direction) {
                    $builder->withSum('taskHour', 'hours')->orderBy('task_hour_sum_hours', $direction->value);
                })
                ->clickable(false),
        ];
    }
}
