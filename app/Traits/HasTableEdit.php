<?php

namespace App\Traits;

use RamonRietdijk\LivewireTables\Columns\ViewColumn;

trait HasTableEdit
{
    public function edit(string $modelId): void
    {
        $this->dispatch('open-update-form-modal', modelId: intval($modelId));
    }

    protected function columns(): array
    {
        return [
            ...$this->modelColumns(),
            ViewColumn::make('Edit', 'components.table-edit-button')
                ->clickable(false),
        ];
    }

    abstract protected function modelColumns(): array;
}
