<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Address;
use App\Models\BankAccount;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(trans('base.name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Split::make([
                            Forms\Components\TextInput::make('email')
                                ->label(trans('base.email'))
                                ->email()
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('phone')
                                ->label(trans('base.phone'))
                                ->tel()
                                ->required()
                                ->maxLength(255),
                        ]),

                        Forms\Components\Split::make([
                            Forms\Components\TextInput::make('registration_number')
                                ->label(trans('base.registration_number'))
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('vat_number')
                                ->label(trans('base.vat'))
                                ->required()
                                ->maxLength(255),
                        ]),

                        Forms\Components\Split::make([
                            Address::getSelectWithNewOption(),
                        ]),

                        Forms\Components\Split::make([
                            Select::make('bank_account_id')
                                ->label(trans('base.bank_account'))
                                ->relationship(
                                    name: 'bankAccount',
                                    modifyQueryUsing: function (Builder $query): void {
                                        $query->where('user_id', auth()->id())
                                            ->orderBy('bank_code')
                                            ->orderBy('bank_name');
                                    }
                                )
                                ->getOptionLabelFromRecordUsing(fn (BankAccount $record): string => "{$record->bank_name}, {$record->account_number} / {$record->bank_code}")
                                ->createOptionForm(BankAccount::getForm())
                                ->createOptionUsing(function (array $data): void {
                                    BankAccountResource::createRecordForCurrentUser($data);
                                })
                                ->createOptionAction(fn (Action $action) => $action->slideOver())
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('base.supplier'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('registration_number')
                    ->label(trans('base.registration_number'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('vat_number')
                    ->label(trans('base.vat'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(trans('base.email'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label(trans('base.phone'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading(trans('base.edit_supplier'))
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
            'index' => Pages\ListSuppliers::route('/'),
        ];
    }

    /**
     * Create supplier record for current user
     *
     * @param  array<string, mixed>  $data
     */
    public static function createSupplierForCurrentUser(array $data): Supplier
    {
        return Supplier::create(Arr::add($data, 'user_id', auth()->id()));
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id());
    }

    public static function getNavigationLabel(): string
    {
        return trans('navigation.suppliers');
    }
}
