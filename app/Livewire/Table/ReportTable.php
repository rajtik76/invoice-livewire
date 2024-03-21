<?php

declare(strict_types=1);

namespace App\Livewire\Table;

use App\Models\Contract;
use App\Models\Report;
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
            Column::make(__('base.year') . '/' . __('base.month'), 'year')
                ->sortable(function (Builder $builder, Direction $direction) {
                    $builder->orderBy('year', $direction->value)->orderBy('month', $direction->value);
                })->displayUsing(function (int $year, Report $report) {
                    return $report->year . '/' . $report->month;
                }),

            // Hours
            Column::make(__('base.hours'), function (Report $report) {
                return collect($report->content)->sum(fn($items) => collect($items)->sum('hours'));
            })->computed(),
            ViewColumn::make(__('base.pdf'), 'components.button-report-download')
                ->clickable(false)
                ->hide(),
            Column::make(__('base.view'), function (Report $report) {
                return '<a href="' . route('view.report', $report->id) . '" target="_blank" class="!p-1 inline-flex items-center px-4 py-2 bg-green-400/20 dark:bg-green-400/10 border border-transparent rounded-md font-semibold text-xs text-green-800 dark:text-green-500 uppercase tracking-widest hover:bg-green-400/60 dark:hover:bg-green-400/25 dark:active:bg-green-700 focus:outline-none focus:ring-2 dark:focus:ring-green-400/20 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">' . trans('base.view') . '</a>';
            })
                ->clickable(false)
                ->asHtml(),
        ];
    }

    public function download(int $reportId): StreamedResponse
    {
        $report = Report::with('contract')->find($reportId);

        return response()->streamDownload(function () use ($report) {
            $pdf = Pdf::loadView('pdf.report', [
                'report' => $report,
            ]);
            echo $pdf->stream();
        }, name: "report-{$report->contract->name}-{$report->year}" . sprintf('%02d', $report->month) . '.pdf');
    }
}
