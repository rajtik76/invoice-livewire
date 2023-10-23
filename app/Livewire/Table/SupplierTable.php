<?php

namespace App\Livewire\Table;

use App\Models\Customer;
use App\Models\Supplier;
use App\Traits\HasTableDeleteAction;
use App\Traits\HasTableEdit;
use App\Traits\HasTableRefreshListener;
use Illuminate\Database\Eloquent\Builder;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;

class SupplierTable extends LivewireTable
{
    use HasTableDeleteAction, HasTableEdit, HasTableRefreshListener;

    protected string $model = Supplier::class;

    protected function query(): Builder
    {
        return parent::query()->currentUser();
    }

    protected function modelColumns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Registration #', 'registration_number')
                ->sortable()
                ->searchable(),
            Column::make('VAT #', 'vat_number')
                ->sortable()
                ->searchable(),
        ];
    }
}