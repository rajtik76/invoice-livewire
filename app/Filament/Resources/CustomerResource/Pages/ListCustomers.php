<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->label(trans('base.create_customer'))
                ->modalHeading(trans('base.create_customer'))
                ->using(function (array $data): void {
                    Customer::create(Arr::add($data, 'user_id', auth()->id()));
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return trans('navigation.customers');
    }
}
