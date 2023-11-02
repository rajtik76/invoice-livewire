<?php

declare(strict_types=1);

namespace App\Livewire\Table;

use App\Models\Contract;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use RamonRietdijk\LivewireTables\Columns\BaseColumn;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;
use RamonRietdijk\LivewireTables\Enums\Direction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceTable extends BaseTable
{
    protected string $model = Invoice::class;

    /** @return BaseColumn[] */
    protected function baseColumns(): array
    {
        return [
            Column::make(__('base.contract'), 'contract_id')
                ->displayUsing(function (mixed $value, Invoice $model): string {
                    return $model->load('contract')->contract->name;
                })
                ->sortable(function (Builder $builder, Direction $direction) {
                    /** @var Builder $contracts */
                    $contracts = Contract::select('id')->whereColumn('contracts.id', 'invoices.contract_id');
                    $builder->orderBy($contracts, $direction->value);
                })
                ->searchable(function (Builder $builder, mixed $search): void {
                    $builder->whereIn('contract_id',
                        Contract::currentUser()
                            ->where('name', 'like', "%{$search}%")
                            ->pluck('id')
                    );
                }),
            Column::make(__('base.number'), 'number')
                ->sortable()
                ->searchable(),
            Column::make(__('base.year'), 'year')->sortable(),
            Column::make(__('base.month'), 'month')->sortable(),
            Column::make(__('base.issue_date'), 'issue_date')
                ->displayUsing(fn(mixed $value, Invoice $model): string => $model->issue_date->toDateString())
                ->sortable(),
            Column::make(__('base.due_date'), 'due_date')
                ->displayUsing(fn(mixed $value, Invoice $model): string => $model->due_date->toDateString())
                ->sortable(),
            Column::make(__('base.amount'), 'total_amount')
                ->displayUsing(function (mixed $value, Invoice $model): string {
                    return "{$model->total_amount} {$model->contract->currency->getCurrencySymbol()}";
                })
                ->sortable()
                ->searchable(),
            ViewColumn::make(__('base.pdf'), 'components.table-download-button')->clickable(false),
        ];
    }

    public function download(int $invoiceId): StreamedResponse
    {
        $invoice = Invoice::find($invoiceId);

        return response()->streamDownload(function () use ($invoice) {
            $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $invoice->load(['contract.supplier', 'contract.customer'])], [], 'utf-8');
            echo $pdf->stream();
        }, name: "invoice-{$invoice->contract->name}-{$invoice->year}" . sprintf('%02d', $invoice->month) . ".pdf");
    }
}
