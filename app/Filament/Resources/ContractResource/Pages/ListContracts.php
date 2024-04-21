<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use App\Models\Contract;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;

class ListContracts extends ListRecords
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(trans('base.create_contract'))
                ->modalHeading(trans('base.create_contract'))
                ->slideOver()
                ->using(function (array $data): Contract {
                    return Contract::create(Arr::add($data, 'user_id', auth()->id()));
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return trans('navigation.contracts');
    }
}
