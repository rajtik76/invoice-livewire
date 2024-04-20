<?php

namespace App\Filament\Resources\AddressResource\Pages;

use App\Filament\Resources\AddressResource;
use App\Models\Address;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Arr;

class ManageAddresses extends ManageRecords
{
    protected static string $resource = AddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->using(function (array $data): void {
                    Address::create(Arr::add($data, 'user_id', auth()->id()));
                }),
        ];
    }
}
