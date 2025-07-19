<?php

use Illuminate\Support\Facades\Route;
use Modules\Accounting\app\Http\Controllers\AccountingController;
use Modules\Accounting\Http\Controllers\AccountController;
use Modules\Accounting\Http\Controllers\AccountTransferController;
use Modules\Accounting\Http\Controllers\CategoryController;
use Modules\Accounting\Http\Controllers\ContainerController;
use Modules\Accounting\Http\Controllers\CustomerController;
use Modules\Accounting\Http\Controllers\ExpenseController;
use Modules\Accounting\Http\Controllers\IncomeController;
use Modules\Accounting\Http\Controllers\InvestmentController;
use Modules\Accounting\Http\Controllers\InvestorController;
use Modules\Accounting\Http\Controllers\InvoiceController;
use Modules\Accounting\Http\Controllers\InvoicePaymentController;
use Modules\Accounting\Http\Controllers\ProductController;
use Modules\Accounting\Http\Controllers\RepaymentController;
use Modules\Accounting\Http\Controllers\ReportController;

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'translation']], function () {
    Route::resource('account', AccountController::class)->names('account');
    Route::resource('invoice', InvoiceController::class)->names('invoice');
    Route::resource('invoice_payments', InvoicePaymentController::class);
    Route::get('invoice-download/{id}', [InvoiceController::class, 'download'])->name('invoice.download');
    Route::get('accounts/{account}/transactions', [AccountController::class, 'transactions'])->name('account.transactions');

    Route::get('invoice_payments/get-data', [InvoicePaymentController::class, 'getInvoicePayments'])->name('invoice_payments.get_data');
    Route::get('invoice_payments/get-outstanding-invoices', [InvoicePaymentController::class, 'getOutstandingInvoices'])->name('invoice_payments.get_outstanding_invoices');
    Route::resource('customer', CustomerController::class)->names('customer');
    Route::put('customer/status-update/{id}', [CustomerController::class, 'statusUpdate'])->name('customer.status.update');
    Route::resource('container', ContainerController::class)->names('container');
    Route::put('container/status-update/{id}', [ContainerController::class, 'statusUpdate'])->name('container.status.update');
    Route::resource('product', ProductController::class)->names('product');
    Route::put('product/status-update/{id}', [ProductController::class, 'statusUpdate'])->name('product.status.update');
    Route::resource('category', CategoryController::class)->names('category');
    Route::put('category/status-update/{id}', [CategoryController::class, 'statusUpdate'])->name('category.status.update');

    Route::resource('income', IncomeController::class)->names('income');
    Route::resource('transfer', AccountTransferController::class)->names('transfer');
    Route::resource('expense', ExpenseController::class)->names('expense');
    Route::resource('investor', InvestorController::class)->names('investor');
    Route::resource('repayment', RepaymentController::class)->names('repayment');
    Route::resource('investment', InvestmentController::class)->names('investment');

    Route::get('reports/invoice', [AccountingController::class, 'invoiceReport'])->name('reports.invoice');
    Route::get('reports/customer', [AccountingController::class, 'customerReport'])->name('reports.customer');
    Route::get('reports/container', [AccountingController::class, 'containerReport'])->name('reports.container');
    Route::get('reports/product', [AccountingController::class, 'productReport'])->name('reports.product');
    Route::get('reports/category', [AccountingController::class, 'categoryReport'])->name('reports.category');
    Route::get('reports/income', [ReportController::class, 'income'])->name('reports.income');
    Route::get('reports/transfer', [AccountingController::class, 'transferReport'])->name('reports.transfer');
    Route::get('reports/expense', [ReportController::class, 'expense'])->name('reports.expense');
    Route::get('reports/investor', [AccountingController::class, 'investorReport'])->name('reports.investor');
    Route::get('reports/income-data', [ReportController::class, 'getIncomeData'])->name('reports.get_income_data');
    Route::get('reports/expense-data', [ReportController::class, 'getExpenseData'])->name('reports.get_expense_data');
});
