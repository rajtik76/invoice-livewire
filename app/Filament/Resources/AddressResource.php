<?php

namespace App\Filament\Resources;

use App\Enums\CountryEnum;
use App\Filament\Resources\AddressResource\Pages;
use App\Models\Address;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('street')
                    ->label(trans('base.street'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->label(trans('base.city'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip')
                    ->label(trans('base.zip'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('country')
                    ->label(trans('base.country'))
                    ->required()
                    ->options(CountryEnum::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('street')
                    ->label(trans('base.street'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label(trans('base.city'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip')
                    ->label(trans('base.zip'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->label(trans('base.country'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (CountryEnum $state): string => $state->countryName()),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAddresses::route('/'),
        ];
    }

    /**
     * Filter current user
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id());
    }
}
