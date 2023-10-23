<?php

namespace App\Livewire\Table;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\Supplier;
use App\Traits\HasTableDeleteAction;
use App\Traits\HasTableEdit;
use App\Traits\HasTableRefreshListener;
use Illuminate\Database\Eloquent\Builder;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;
use RamonRietdijk\LivewireTables\Filters\BaseFilter;
use RamonRietdijk\LivewireTables\Filters\BooleanFilter;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;

class ContractTable extends LivewireTable
{
    use HasTableDeleteAction, HasTableEdit, HasTableRefreshListener;

    protected string $model = Contract::class;

    protected function query(): Builder
    {
        return parent::query()->currentUser();
    }

    protected function modelColumns(): array
    {
        return [
            ViewColumn::make('Active', 'components.table-state'),
            Column::make('Customer', 'customer_id')
                ->displayUsing(function (mixed $value, Contract $model): string {
                    return $model->load('customer')->customer->name;
                })
                ->searchable(function (Builder $builder, mixed $search): void {
                    $builder->whereIn('customer_id',
                        Customer::currentUser()
                            ->where('name', 'like', "%{$search}%")
                            ->pluck('id')
                    );
                }),
            Column::make('Supplier', 'supplier_id')
                ->displayUsing(function (mixed $value, Contract $model): string {
                    return $model->load('supplier')->supplier->name;
                })
                ->searchable(function (Builder $builder, mixed $search): void {
                    $builder->whereIn('supplier_id',
                        Supplier::currentUser()
                            ->where('name', 'like', "%{$search}%")
                            ->pluck('id')
                    );
                }),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Signed', 'signed_at')
                ->displayUsing(function (mixed $value, Contract $model): string {
                    return $model->signed_at?->toDateString();
                })
                ->sortable(),
            Column::make('Price', 'price_per_hour')
                ->displayUsing(function (mixed $value, Contract $model): string {
                    return "{$model->price_per_hour} {$model->currency->getCurrencySymbol()}";
                })
                ->sortable(),
        ];
    }

    /**
     * @return array<int, BaseFilter>
     */
    protected function filters(): array
    {
        return [
            BooleanFilter::make('Active', 'active'),
        ];
    }
}
