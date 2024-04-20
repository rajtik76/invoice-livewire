<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\Invoice;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Arr;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->using(function (array $data): Invoice {
                    return Invoice::create(Arr::add($data, 'user_id', auth()->id()));
                }),
        ];
    }
}
