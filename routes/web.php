<?php

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

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('table/address', 'table.address')->name('table.address');
    Route::view('table/bank-account', 'table.bank-account')->name('table.bank-account');
    Route::view('table/customer', 'table.customer')->name('table.customer');
    Route::view('table/supplier', 'table.supplier')->name('table.supplier');
    Route::view('table/contract', 'table.contract')->name('table.contract');
    Route::view('table/task', 'table.task')->name('table.task');
    Route::view('table/taskHour/{task?}', 'table.taskHour')->name('table.taskHour');
});

require __DIR__.'/auth.php';
