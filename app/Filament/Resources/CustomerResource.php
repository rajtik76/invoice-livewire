<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Address;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema([
                        Address::getSelectWithNewOption(),

                        Forms\Components\Split::make([
                            Forms\Components\TextInput::make('name')
                                ->label(trans('base.customer'))
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('registration_number')
                                ->label(trans('base.registration'))
                                ->maxLength(255)
                                ->default(null),

                            Forms\Components\TextInput::make('vat_number')
                                ->label(trans('base.vat'))
                                ->required()
                                ->maxLength(255),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('base.customer'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('registration_number')
                    ->label(trans('base.registration'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('vat_number')
                    ->label(trans('base.vat'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
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
            'index' => Pages\ListCustomers::route('/'),
        ];
    }

    /**
     * Get only current user records
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['address'])
            ->where('user_id', auth()->id());
    }
}
