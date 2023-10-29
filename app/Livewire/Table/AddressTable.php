<?php

declare(strict_types=1);

namespace App\Livewire\Table;

use App\Enums\CountryEnum;
use App\Models\Address;
use RamonRietdijk\LivewireTables\Columns\BaseColumn;
use RamonRietdijk\LivewireTables\Columns\Column;

class AddressTable extends BaseTable
{
    protected string $model = Address::class;

    /** @return BaseColumn[] */
    protected function baseColumns(): array
    {
        return [
            Column::make(__('base.street'), 'street')->searchable(),
            Column::make(__('base.city'), 'city'),
            Column::make(__('base.zip'), 'zip'),
            Column::make(__('base.country'), 'country')->displayUsing(function (CountryEnum $value) {
                return $value->countryName();
            }),
        ];
    }
}
