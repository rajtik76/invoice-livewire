<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Contracts\View\View;

class ViewReportController extends Controller
{
    public function __invoke(Report $report): View
    {
        return view('report', ['report' => $report]);
    }
}
