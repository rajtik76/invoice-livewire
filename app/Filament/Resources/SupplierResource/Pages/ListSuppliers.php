<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListSuppliers extends ListRecords
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(trans('page.create_supplier'))
                ->modalHeading(trans('page.create_supplier'))
                ->slideOver()
                ->using(function (array $data): void {
                    self::$resource::createSupplierForCurrentUser($data);
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return trans('navigation.suppliers');
    }
}
