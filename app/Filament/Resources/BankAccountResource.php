<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankAccountResource\Pages;
use App\Models\BankAccount;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class BankAccountResource extends Resource
{
    protected static ?string $model = BankAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(BankAccount::getForm());
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
                    ->label(trans('base.bank_account'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_code')
                    ->label(trans('base.bank_code'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('iban')
                    ->label('IBAN')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('swift')
                    ->label('SWIFT')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->modalHeading(trans('base.edit_bank_account')),
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

    /**
     * Create bank account record for current user
     *
     * @param  array  $data  <string, mixed>
     */
    public static function createRecordForCurrentUser(array $data): BankAccount
    {
        return BankAccount::create(Arr::add($data, 'user_id', auth()->id()));
    }

    public static function getNavigationLabel(): string
    {
        return trans('navigation.bank_accounts');
    }
}
