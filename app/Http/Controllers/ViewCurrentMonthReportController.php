<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Contracts\View\View;

class ViewCurrentMonthReportController extends Controller
{
    public function __invoke(Contract $contract): View
    {
        $year = intval(now()->format('Y'));
        $month = intval(now()->format('m'));

        $report = new Report([
            'contract_id' => $contract->id,
            'year' => $year,
            'month' => $month,
            'user_id' => auth()->user()->id,
            'content' => ReportService::createContent(
                contract: $contract,
                year: $year,
                month: $month
            )
        ]);

        return view('view.report', ['report' => $report]);
    }
}
