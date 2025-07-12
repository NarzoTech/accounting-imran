<?php

use Illuminate\Support\Facades\Route;
use Modules\Accounting\app\Http\Controllers\AccountingController;
use Modules\Accounting\Http\Controllers\CategoryController;
use Modules\Accounting\Http\Controllers\ContainerController;
use Modules\Accounting\Http\Controllers\CustomerController;
use Modules\Accounting\Http\Controllers\InvestorController;
use Modules\Accounting\Http\Controllers\ProductController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::resource('invoice', AccountingController::class)->names('invoice');
    Route::resource('customer', CustomerController::class)->names('customer');
    Route::put('customer/status-update/{id}', [CustomerController::class, 'statusUpdate'])->name('customer.status.update');
    Route::resource('container', ContainerController::class)->names('container');
    Route::put('container/status-update/{id}', [ContainerController::class, 'statusUpdate'])->name('container.status.update');
    Route::resource('product', ProductController::class)->names('product');
    Route::put('product/status-update/{id}', [ProductController::class, 'statusUpdate'])->name('product.status.update');
    Route::resource('category', CategoryController::class)->names('category');
    Route::put('category/status-update/{id}', [CategoryController::class, 'statusUpdate'])->name('category.status.update');

    Route::resource('income', AccountingController::class)->names('income');
    Route::resource('transfer', AccountingController::class)->names('transfer');
    Route::resource('expense', AccountingController::class)->names('expense');
    Route::resource('investor', InvestorController::class)->names('investor');
    Route::resource('repayment', AccountingController::class)->names('repayment');

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
