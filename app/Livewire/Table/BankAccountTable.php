<?php

declare(strict_types=1);

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
            Column::make(__('base.bank_name'), 'bank_name')
                ->sortable()
                ->searchable(),
            Column::make(__('base.account'), 'account_number')
                ->sortable()
                ->searchable(),
            Column::make(__('base.bank_code'), 'bank_code')
                ->sortable()
                ->searchable(),
            Column::make(__('base.iban'), 'iban')
                ->sortable()
                ->searchable(),
            Column::make(__('base.swift'), 'swift')
                ->sortable()
                ->searchable(),
        ];
    }
}
