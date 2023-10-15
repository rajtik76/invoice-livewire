<?php

namespace App\Livewire\Table;

use App\Enums\CountryEnum;
use App\Models\Address;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use RamonRietdijk\LivewireTables\Actions\Action;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;

class AddressTable extends LivewireTable
{
    protected string $model = Address::class;

    public function editAddress(string $address): void
    {
        $this->dispatch('address-update-form-modal-open', address: intval($address));
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
            Column::make('Edit', function (mixed $value, Address $model) {
                return '<button class="bg-blue-400 hover:bg-blue-500 dark:bg-blue-900 dark:hover:bg-blue-800 px-2 py-0.5 rounded" wire:click="editAddress(' . $model->id . ')">Edit</button>';
            })
                ->asHtml()
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
