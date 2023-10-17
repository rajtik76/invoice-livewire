<?php

namespace App\Livewire\Table;

use App\Enums\CountryEnum;
use App\Models\Address;
use App\Traits\HasFormModalListener;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use RamonRietdijk\LivewireTables\Actions\Action;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;

class AddressTable extends LivewireTable
{
    use HasFormModalListener;

    protected string $model = Address::class;

    public function editAddress(string $address): void
    {
        $this->dispatch('open-update-form-modal', modelId: intval($address));
    }

    protected function query(): Builder
    {
        return parent::query()->currentUser();
    }

    protected function columns(): array
    {
        return [
            Column::make('Street', 'street')->searchable(),
            Column::make('City', 'city'),
            Column::make('Zip', 'zip'),
            Column::make('Country', 'country')->displayUsing(function (CountryEnum $value) {
                return $value->countryName();
            }),
            ViewColumn::make('Edit', 'address.edit')
                ->clickable(false),
        ];
    }

    protected function actions(): array
    {
        return [
            Action::make('Delete', 'delete', function (Collection $models) {
                $models->each(function (Address $address) {
                    if (auth()->user()->cannot('delete', $address)) {
                        abort(403);
                    }

                    return $address->delete();
                });
            }),
        ];
    }
}