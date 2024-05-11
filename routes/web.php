<?php

use App\Http\Controllers\DashboardController;
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

//Route::get('/', fn () => to_route('login'));
//
//Route::view('profile', 'profile')
//    ->middleware(['auth'])
//    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    //    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('view/report/{report}', ViewReportController::class)->name('view.report');
    Route::get('view/invoice/{invoice}', ViewInvoiceController::class)->name('view.invoice');
    //
    //    Route::view('table/address', 'table.address')->name('table.address');
    //    Route::view('table/bank-account', 'table.bank-account')->name('table.bank-account');
    //    Route::view('table/customer', 'table.customer')->name('table.customer');
    //    Route::view('table/supplier', 'table.supplier')->name('table.supplier');
    //    Route::view('table/contract', 'table.contract')->name('table.contract');
    //    Route::view('table/task', 'table.task')->name('table.task');
    //    Route::view('table/taskHour/{task?}', 'table.taskHour')->name('table.taskHour');
    //    Route::view('table/invoice', 'table.invoice')->name('table.invoice');
    //    Route::view('table/report', 'table.report')->name('table.report');
});

//require __DIR__.'/auth.php';
