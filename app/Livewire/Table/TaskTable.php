<?php

namespace App\Livewire\Table;

use App\Models\Contract;
use App\Models\Task;
use App\Traits\HasTableDeleteAction;
use App\Traits\HasTableEdit;
use App\Traits\HasTableRefreshListener;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Enumerable;
use RamonRietdijk\LivewireTables\Actions\Action;
use RamonRietdijk\LivewireTables\Actions\BaseAction;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;
use RamonRietdijk\LivewireTables\Enums\Direction;
use RamonRietdijk\LivewireTables\Filters\BaseFilter;
use RamonRietdijk\LivewireTables\Filters\BooleanFilter;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;

class TaskTable extends LivewireTable
{
    use HasTableDeleteAction, HasTableEdit, HasTableRefreshListener;

    protected string $model = Task::class;

    protected function query(): Builder
    {
        return parent::query()->currentUser();
    }

    protected function modelColumns(): array
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
                            ->whereColumn('contracts.id', 'tasks.contract_id')
                        , $direction->value);
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

    /**
     * @return array<int, BaseFilter>
     */
    protected function filters(): array
    {
        return [
            BooleanFilter::make('Active', 'active'),
        ];
    }

    /**
     * @return BaseAction[]
     */
    protected function actions(): array
    {
        return [
            Action::make('Deactivate', 'deactivate', function (Enumerable $models): void {
                foreach ($models as $model) {
                    $model->update(['active' => false]);
                }
            }),
            Action::make('Activate', 'activate', function (Enumerable $models): void {
                foreach ($models as $model) {
                    $model->update(['active' => true]);
                }
            })
        ];
    }
}
