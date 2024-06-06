<?php

use App\Http\Controllers\ViewCurrentMonthReportController;
use App\Http\Controllers\ViewInvoiceController;
use App\Http\Controllers\ViewReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('view/report/{report}', ViewReportController::class)->name('view.report');
    Route::get('view/current-month/report/{contract}', ViewCurrentMonthReportController::class)->name('view.current-month-report');
    Route::get('view/invoice/{invoice}', ViewInvoiceController::class)->name('view.invoice');
});

//require __DIR__.'/auth.php';
