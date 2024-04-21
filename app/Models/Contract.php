<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\KeyValueOptions;
use App\Enums\CurrencyEnum;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\SupplierResource;
use App\Traits\HasCurrentUserScope;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model implements KeyValueOptions
{
    use HasCurrentUserScope, HasFactory;

    protected $guarded = [];

    protected $casts = [
        'signed_at' => 'date',
        'currency' => CurrencyEnum::class,
    ];

    /**
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return self::currentUser()
            ->orderBy('name')
            ->get()
            ->keyBy('id')
            ->map(fn (Contract $contract) => $contract->name)
            ->toArray();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get form
     *
     * @return array<int, mixed>
     */
    public static function getForm(): array
    {
        return [
            Forms\Components\Select::make('customer_id')
                ->label(trans('base.customer'))
                ->relationship(
                    name: 'customer',
                    titleAttribute: 'name',
                    modifyQueryUsing: function (Builder $query): void {
                        $query->where('user_id', auth()->id())
                            ->orderBy('name');
                    }
                )
                ->createOptionModalHeading(trans('base.create_customer'))
                ->createOptionForm(Customer::getForm())
                ->createOptionUsing(function (array $data): void {
                    CustomerResource::createRecordForCurrentUser($data);
                })
                ->createOptionAction(fn (Action $action) => $action->slideOver())
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('supplier_id')
                ->label(trans('base.supplier'))
                ->relationship(
                    name: 'supplier',
                    titleAttribute: 'name',
                    modifyQueryUsing: function (Builder $query): void {
                        $query->where('user_id', auth()->id())
                            ->orderBy('name');
                    }
                )
                ->createOptionModalHeading(trans('base.create_supplier'))
                ->createOptionForm(Supplier::getForm())
                ->createOptionUsing(function (array $data): void {
                    SupplierResource::createRecordForCurrentUser($data);
                })
                ->createOptionAction(fn (Action $action) => $action->slideOver())
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('name')
                ->label(trans('base.contract_name'))
                ->required()
                ->maxLength(255),

            Forms\Components\DatePicker::make('signed_at')
                ->label(trans('base.signed_at'))
                ->required()
                ->default(now()),

            Forms\Components\TextInput::make('price_per_hour')
                ->label(trans('base.price_per_hour'))
                ->required()
                ->numeric(),

            Forms\Components\Select::make('currency')
                ->label(trans('base.currency'))
                ->required()
                ->options(CurrencyEnum::class),

            Forms\Components\Toggle::make('active')
                ->label(trans('base.active'))
                ->required()
                ->default(true),
        ];
    }
}
