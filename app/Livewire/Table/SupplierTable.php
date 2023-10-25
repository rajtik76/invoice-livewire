<?php
declare(strict_types=1);

namespace App\Livewire\Table;

use App\Models\Supplier;
use RamonRietdijk\LivewireTables\Columns\BaseColumn;
use RamonRietdijk\LivewireTables\Columns\Column;

class SupplierTable extends BaseTable
{
    protected string $model = Supplier::class;

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
            Column::make('VAT #', 'vat_number')
                ->sortable()
                ->searchable(),
        ];
    }
}
