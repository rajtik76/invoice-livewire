<?php

declare(strict_types=1);

namespace App\Livewire\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Enumerable;
use Livewire\Attributes\On;
use RamonRietdijk\LivewireTables\Actions\Action;
use RamonRietdijk\LivewireTables\Actions\BaseAction;
use RamonRietdijk\LivewireTables\Columns\BaseColumn;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;
use RamonRietdijk\LivewireTables\Filters\BaseFilter;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;

abstract class BaseTable extends LivewireTable
{
    #[On('model-updated')]
    public function refreshData(): void
    {
        $this->dispatch('refreshLivewireTable');
    }

    /**
     * Always use current user scope for model
     */
    protected function query(): Builder
    {
        return parent::query()->where('user_id', auth()->id());
    }

    /**
     * Child class defined actions
     *
     * @return BaseAction[]
     */
    protected function baseActions(): array
    {
        return [];
    }

    /**
     * @return BaseAction[]
     */
    protected function actions(): array
    {
        return [
            ...$this->baseActions(),
            Action::make(__('base.delete'), 'delete', function (Enumerable $models) {
                $models->each(function (Model $model) {
                    $this->authorize('delete', $model);

                    return $model->delete();
                });
            }),
        ];
    }

    /**
     * Child class defined filters
     *
     * @return BaseFilter[]
     */
    protected function baseFilters(): array
    {
        return [];
    }

    /**
     * @return BaseFilter[]
     */
    protected function filters(): array
    {
        return [
            ...$this->baseFilters(),
        ];
    }

    /**
     * Child class defined columns
     *
     * @return BaseColumn[]
     */
    abstract protected function baseColumns(): array;

    /**
     * @return BaseColumn[]
     */
    protected function columns(): array
    {
        return [
            ...$this->baseColumns(),
            ViewColumn::make(__('base.edit'), 'components.table-edit-button')
                ->clickable(false),
        ];
    }

    /**
     * Edit action open modal form
     */
    public function edit(string $modelId): void
    {
        $this->dispatch('open-update-form-modal', modelId: intval($modelId));
    }
}
