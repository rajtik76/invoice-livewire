<?php

namespace App\Filament\Resources;

use App\Enums\CurrencyEnum;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Contract;
use App\Models\Invoice;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('contract_id')
                            ->options(Contract::where('user_id', auth()->id())->pluck('name', 'id'))
                            ->nullable(false),
                        Split::make([
                            TextInput::make('number')
                                ->numeric()
                                ->required(),
                            TextInput::make('year')
                                ->numeric()
                                ->required()
                                ->default(now()->year),
                            TextInput::make('month')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->maxValue(12)
                                ->default(now()->month),
                        ]),
                        Split::make([
                            DatePicker::make('issue_date')
                                ->required()
                                ->default(now()),
                            DatePicker::make('due_date')
                                ->required()
                                ->default(now()->addDays(7)),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('number', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('contract.name'),
                Tables\Columns\TextColumn::make('number')
                    ->label(trans('base.invoice').' #')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->label('Period')
                    ->sortable(['month', 'year'])
                    ->formatStateUsing(function ($state, Invoice $invoice) {
                        return "{$invoice->year}/{$invoice->month}";
                    }),
                Tables\Columns\TextColumn::make('issue_date')->date('d.m.Y'),
                Tables\Columns\TextColumn::make('due_date')->date('d.m.Y'),
                Tables\Columns\TextColumn::make('due_date')->date('d.m.Y'),
                Tables\Columns\TextColumn::make('total_amount')->money(
                    currency: fn (Invoice $invoice) => $invoice->contract->currency->value,
                    locale: fn (Invoice $invoice) => $invoice->contract->currency === CurrencyEnum::EUR ? 'de' : 'cs'
                ),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make('edit')
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
            'index' => Pages\ListInvoices::route('/'),
            //            'create' => Pages\CreateInvoice::route('/create'),
            //            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['contract'])
            ->where('user_id', auth()->id());
    }
}
