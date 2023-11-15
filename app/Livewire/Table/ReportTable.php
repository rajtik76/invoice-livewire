<?php

declare(strict_types=1);

namespace App\Livewire\Table;

use App\Models\Contract;
use App\Models\Report;
use App\Models\TaskHour;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use RamonRietdijk\LivewireTables\Columns\BaseColumn;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;
use RamonRietdijk\LivewireTables\Enums\Direction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportTable extends BaseTable
{
    protected string $model = Report::class;

    /** @return BaseColumn[] */
    protected function baseColumns(): array
    {
        return [
            Column::make(__('base.contract'), 'contract_id')
                ->displayUsing(function (mixed $value, Report $model): string {
                    return $model->load('contract')->contract->name;
                })
                ->sortable(function (Builder $builder, Direction $direction) {
                    /** @var Builder $contracts */
                    $contracts = Contract::select('id')->whereColumn('contracts.id', 'reports.contract_id');
                    $builder->orderBy($contracts, $direction->value);
                })
                ->searchable(function (Builder $builder, mixed $search): void {
                    $builder->whereIn('contract_id',
                        Contract::currentUser()
                            ->where('name', 'like', "%{$search}%")
                            ->pluck('id')
                    );
                }),
            Column::make(__('base.year'), 'year')
                ->sortable(),
            Column::make(__('base.month'), 'month')
                ->sortable(),
            Column::make(__('base.hours'), 'contract_id')
                ->displayUsing(function ($value) {
                    return TaskHour::whereIn('task_id', Contract::find($value)
                        ->tasks()
                        ->pluck('id')
                    )->sum('hours');
                })
                ->computed(),
            ViewColumn::make(__('base.pdf'), 'components.table-download-button')->clickable(false),
        ];
    }

    public function download(int $report): StreamedResponse
    {
        $report = Report::with('contract')->find($report);

        return response()->streamDownload(function () use ($report) {
            $pdf = Pdf::loadView('pdf.report', [
                'report' => $report,
            ]);
            echo $pdf->stream();
        }, name: "report-{$report->contract->name}-{$report->year}".sprintf('%02d', $report->month).'.pdf');
    }
}
