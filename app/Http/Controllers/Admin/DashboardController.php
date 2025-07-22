<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountTransaction;
use Modules\Accounting\Models\Customer;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\InvoicePayment;
use Modules\Accounting\Models\Product;

class DashboardController extends Controller
{
    /**
     * @param Request $request
     */
    public function dashboard(Request $request)
    {
        // Get the filter from the request, default to 'month'
        $filter = $request->input('filter', 'month');

        // Determine date range based on filter
        $startDate = Carbon::now()->startOfMonth(); // Default to month
        $endDate = Carbon::now()->endOfMonth(); // Default to month

        $filterText = 'This Month'; // Default display text for dropdowns

        switch ($filter) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $filterText = 'This Week';
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $filterText = 'This Month';
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $filterText = 'This Year';
                break;
                // No specific cases for 'last7days', 'last30days' as they align with 'week' and 'month'
                // if we are using the same filter logic for all cards.
                // If you need truly separate filters for expense breakdown, you'd need another request parameter.
        }

        // Common data for all filters
        $totalBalance = Account::sum('balance');
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();

        // Data that needs to be filtered by date range
        $inflows = AccountTransaction::where('type', 'deposit')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $outflows = AccountTransaction::where('type', 'expense')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $totalInvoicesAmount = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
            ->sum('total_amount');

        $countInvoices = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
            ->count();

        $totalCollection = InvoicePayment::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // Expense Breakdown now also uses the main $startDate and $endDate
        $totalExpensesCurrentPeriod = AccountTransaction::where('type', 'expense')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $invoicesInPeriod = Invoice::whereBetween('invoice_date', [$startDate, $endDate])->get();

        $customersDue = 0;
        foreach ($invoicesInPeriod as $invoice) {
            $totalPaidForInvoice = $invoice->payments()->sum('amount');
            $totalDiscount = $invoice->discount_amount;
            $customersDue += ($invoice->total_amount - $totalPaidForInvoice) - $totalDiscount;
        }

        return view('admin.dashboard', compact(
            'totalBalance',
            'inflows',
            'outflows',
            'totalInvoicesAmount',
            'countInvoices',
            'totalCollection',
            'totalCustomers',
            'totalExpensesCurrentPeriod',
            'totalProducts',
            'customersDue',
            'filter',
            'filterText'
        ));
    }

    public function setLanguage()
    {
        $action = setLanguage(request('code'));

        if ($action) {
            $notification = __('Language Changed Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->back()->with($notification);
        }

        $notification = __('Language Changed Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function setCurrency()
    {
        $currency = allCurrencies()->where('currency_code', request('currency'))->first();

        if (session()->has('currency_code')) {
            session()->forget('currency_code');
            session()->forget('currency_position');
            session()->forget('currency_icon');
            session()->forget('currency_rate');
        }
        if ($currency) {
            session()->put('currency_code', $currency->currency_code);
            session()->put('currency_position', $currency->currency_position);
            session()->put('currency_icon', $currency->currency_icon);
            session()->put('currency_rate', $currency->currency_rate);

            $notification = __('Currency Changed Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->back()->with($notification);
        }
        getSessionCurrency();
        $notification = __('Currency Changed Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
}
