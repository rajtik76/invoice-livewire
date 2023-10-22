<?php

namespace App\Livewire\Table;

use App\Enums\CountryEnum;
use App\Models\Address;
use App\Traits\HasTableDeleteAction;
use App\Traits\HasTableEdit;
use App\Traits\HasTableRefreshListener;
use Illuminate\Database\Eloquent\Builder;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;

class AddressTable extends LivewireTable
{
    use HasTableRefreshListener, HasTableDeleteAction, HasTableEdit;

    protected string $model = Address::class;

    protected function query(): Builder
    {
        return parent::query()->currentUser();
    }

    protected function modelColumns(): array
    {
        return [
            Column::make('Street', 'street')->searchable(),
            Column::make('City', 'city'),
            Column::make('Zip', 'zip'),
            Column::make('Country', 'country')->displayUsing(function (CountryEnum $value) {
                return $value->countryName();
            }),
        ];
    }
}
