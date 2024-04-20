<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankAccountResource\Pages;
use App\Models\BankAccount;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BankAccountResource extends Resource
{
    protected static ?string $model = BankAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        Split::make([
                            TextInput::make('bank_name')
                                ->label(trans('base.bank_name'))
                                ->required()
                                ->maxLength(255),
                        ]),
                        Split::make([
                            Split::make([
                                TextInput::make('account_number')
                                    ->label(trans('base.account'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('bank_code')
                                    ->label(trans('base.bank_code'))
                                    ->required()
                                    ->maxLength(255)
                                    ->grow(false),
                            ]),
                        ]),
                        Split::make([
                            TextInput::make('iban')
                                ->label(trans('base.iban'))
                                ->required()
                                ->maxLength(255),
                            TextInput::make('swift')
                                ->label(trans('base.swift'))
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
                Tables\Columns\TextColumn::make('bank_name')
                    ->label(trans('base.bank_name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_number')
                    ->label(trans('base.account'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_code')
                    ->label(trans('base.bank_code'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('iban')
                    ->label(trans('base.iban'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('swift')
                    ->label(trans('base.swift'))
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
            'index' => Pages\ListBankAccounts::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id());
    }
}
