<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;

class ViewInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice): View
    {
        return view('view.invoice', [
            'invoice' => $invoice,
            'invoiceCurrencySymbol' => $invoice->contract->currency->getCurrencySymbol(),
            'invoiceTotalHours' => collect($invoice->content)->sum('hours'),
            'invoiceTotalAmount' => collect($invoice->content)->sum('amount'),
        ]);
    }
}
