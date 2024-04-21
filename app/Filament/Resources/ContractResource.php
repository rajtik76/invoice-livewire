<?php

namespace App\Filament\Resources;

use App\Enums\CurrencyEnum;
use App\Filament\Resources\ContractResource\Pages;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(trans('base.customer'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('supplier.name')
                    ->label(trans('base.supplier'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(trans('base.contract'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('signed_at')
                    ->label(trans('base.signed_at'))
                    ->date('d.m.Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_per_hour')
                    ->label(trans('base.price_per_hour'))
                    ->formatStateUsing(fn (Contract $record) => Number::currency($record->price_per_hour, $record->currency->value, app()->getLocale()))
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('active')
                    ->label(trans('base.active')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading(trans('base.edit_contract'))
                    ->slideOver(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContracts::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id());
    }

    public static function getNavigationLabel(): string
    {
        return trans('navigation.contracts');
    }
}
