<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ReportLayout extends Component
{
    public function render(): View
    {
        app()->setLocale(request()->get('locale'));

        return view('layouts.report');
    }
}
