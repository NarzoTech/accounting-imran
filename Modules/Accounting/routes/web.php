<?php

use Illuminate\Support\Facades\Route;
use Modules\Accounting\app\Http\Controllers\AccountingController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::resource('invoice', AccountingController::class)->names('invoice');
    Route::resource('customer', AccountingController::class)->names('customer');
    Route::resource('container', AccountingController::class)->names('container');
    Route::resource('product', AccountingController::class)->names('product');
    Route::resource('category', AccountingController::class)->names('category');

    Route::resource('income', AccountingController::class)->names('income');
    Route::resource('transfer', AccountingController::class)->names('transfer');
    Route::resource('expense', AccountingController::class)->names('expense');
    Route::resource('investor', AccountingController::class)->names('investor');

    Route::get('reports/invoice', [AccountingController::class, 'invoiceReport'])->name('reports.invoice');
    Route::get('reports/customer', [AccountingController::class, 'customerReport'])->name('reports.customer');
    Route::get('reports/container', [AccountingController::class, 'containerReport'])->name('reports.container');
    Route::get('reports/product', [AccountingController::class, 'productReport'])->name('reports.product');
    Route::get('reports/category', [AccountingController::class, 'categoryReport'])->name('reports.category');
    Route::get('reports/income', [AccountingController::class, 'incomeReport'])->name('reports.income');
    Route::get('reports/transfer', [AccountingController::class, 'transferReport'])->name('reports.transfer');
    Route::get('reports/expense', [AccountingController::class, 'expenseReport'])->name('reports.expense');
    Route::get('reports/investor', [AccountingController::class, 'investorReport'])->name('reports.investor');
});
