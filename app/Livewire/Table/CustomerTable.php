<?php

namespace App\Livewire\Table;

use App\Models\Customer;
use RamonRietdijk\LivewireTables\Columns\BaseColumn;
use RamonRietdijk\LivewireTables\Columns\Column;

class CustomerTable extends BaseTable
{
    protected string $model = Customer::class;

    /** @return BaseColumn[] */
    protected function baseColumns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Registration #', 'registration_number')
                ->sortable()
                ->searchable(),
        ];
    }
}
