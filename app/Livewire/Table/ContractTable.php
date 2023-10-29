<?php

declare(strict_types=1);

namespace App\Livewire\Table;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\Supplier;
use App\Traits\HasActiveActions;
use App\Traits\HasActiveFilter;
use Illuminate\Database\Eloquent\Builder;
use RamonRietdijk\LivewireTables\Columns\BaseColumn;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;

class ContractTable extends BaseTable
{
    use HasActiveActions, HasActiveFilter;

    protected string $model = Contract::class;

    /** @return BaseColumn[] */
    protected function baseColumns(): array
    {
        return [
            ViewColumn::make(__('base.active'), 'components.table-state'),
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
            Column::make(__('base.supplier'), 'supplier_id')
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
            Column::make(__('base.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make('Signed', 'signed_at')
                ->displayUsing(function (mixed $value, Contract $model): string {
                    return $model->signed_at->toDateString();
                })
                ->sortable(),
            Column::make(__('base.price'), 'price_per_hour')
                ->displayUsing(function (mixed $value, Contract $model): string {
                    return "{$model->price_per_hour} {$model->currency->getCurrencySymbol()}";
                })
                ->sortable(),
        ];
    }
}
