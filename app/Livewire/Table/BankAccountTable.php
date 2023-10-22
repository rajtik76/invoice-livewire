<?php

namespace App\Livewire\Table;

use App\Models\BankAccount;
use App\Traits\HasTableDeleteAction;
use App\Traits\HasTableEdit;
use App\Traits\HasTableRefreshListener;
use Illuminate\Database\Eloquent\Builder;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;

class BankAccountTable extends LivewireTable
{
    use HasTableDeleteAction, HasTableEdit, HasTableRefreshListener;

    protected string $model = BankAccount::class;

    protected function query(): Builder
    {
        return parent::query()->currentUser();
    }

    protected function modelColumns(): array
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
