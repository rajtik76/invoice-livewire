<?php

declare(strict_types=1);

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
            Column::make(__('base.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('base.registration').' #', 'registration_number')
                ->sortable()
                ->searchable(),
        ];
    }
}
