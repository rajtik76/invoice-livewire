<?php

namespace App\Livewire\Table;

use App\Models\BankAccount;
use RamonRietdijk\LivewireTables\Columns\BaseColumn;
use RamonRietdijk\LivewireTables\Columns\Column;

class BankAccountTable extends BaseTable
{
    protected string $model = BankAccount::class;

    /** @return BaseColumn[] */
    protected function baseColumns(): array
    {
        return [
            Column::make('Bank name', 'bank_name')
                ->sortable()
                ->searchable(),
            Column::make('Account #', 'account_number')
                ->sortable()
                ->searchable(),
            Column::make('Bank #', 'bank_number')
                ->sortable()
                ->searchable(),
            Column::make('IBAN', 'iban')
                ->sortable()
                ->searchable(),
            Column::make('SWIFT', 'swift')
                ->sortable()
                ->searchable(),
        ];
    }
}
