<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountTransaction;
use Modules\Accounting\Models\InvoicePayment;

class ReportController extends Controller
{
    /**
     * Display the Income Report (initial page load).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function income(Request $request)
    {
        // Default date range: current month
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Load account names for display in the report
        // Note: For server-side processing, you might not strictly need to pass all accounts to the view
        // if account name resolution happens entirely on the server. However, it's harmless.
        $accounts = Account::all()->keyBy('id');

        return view('accounting::report.income', compact(
            'startDate',
            'endDate',
            'accounts'
        ));
    }

    /**
     * Get Income Data for DataTables Server-Side Processing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIncomeData(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');
        $columns = $request->input('columns');

        // Get filter dates from request (sent by DataTables AJAX if configured)
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ensure dates are valid Carbon instances for querying, fallback to default if not provided
        $carbonStartDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
        $carbonEndDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfMonth()->endOfDay();

        // --- Fetch Income from AccountTransactions (deposits) ---
        $depositsQuery = AccountTransaction::where('type', 'deposit')
            ->whereBetween('created_at', [$carbonStartDate, $carbonEndDate]);

        // --- Fetch Income from InvoicePayments ---
        $invoicePaymentsQuery = InvoicePayment::whereBetween('created_at', [$carbonStartDate, $carbonEndDate]);

        // Clone queries for filtered count before applying limit/offset
        $depositsFilteredQuery = clone $depositsQuery;
        $invoicePaymentsFilteredQuery = clone $invoicePaymentsQuery;

        // Apply global search filter
        if (!empty($searchValue)) {
            $depositsQuery->where(function ($query) use ($searchValue) {
                $query->where('note', 'like', '%' . $searchValue . '%')
                    ->orWhere('amount', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('account', function ($q) use ($searchValue) {
                        $q->where('name', 'like', '%' . $searchValue . '%');
                    });
            });
            $invoicePaymentsQuery->where(function ($query) use ($searchValue) {
                $query->where('note', 'like', '%' . $searchValue . '%')
                    ->orWhere('amount', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('account', function ($q) use ($searchValue) {
                        $q->where('name', 'like', '%' . $searchValue . '%');
                    });
            });
        }

        // Count total records (without any filters)
        $totalRecords = AccountTransaction::where('type', 'deposit')->count() + InvoicePayment::count();

        // Get filtered counts
        $recordsFiltered = $depositsQuery->count() + $invoicePaymentsQuery->count();

        // Fetch data with relationships (e.g., account)
        $deposits = $depositsQuery->with('account')->get();
        $invoicePayments = $invoicePaymentsQuery->with('account')->get();

        $allIncomeTransactions = collect()
            ->merge($deposits->map(function ($item) {
                $item->source_type = 'deposit'; // Add source type for identification
                return $item;
            }))
            ->merge($invoicePayments->map(function ($item) {
                $item->source_type = 'invoice_payment'; // Add source type for identification
                return $item;
            }));

        // Apply sorting to the combined collection
        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];
            $allIncomeTransactions = $allIncomeTransactions->sortBy(function ($item) use ($orderColumnName) {
                // Handle sorting by related models or specific fields
                if ($orderColumnName === 'account') {
                    return $item->account->name ?? '';
                }
                return $item->{$orderColumnName};
            }, SORT_REGULAR, $orderDir === 'desc');
        }

        // Apply pagination (slice the collection)
        $paginatedIncomeTransactions = $allIncomeTransactions->slice($start)->take($length);

        $data = $paginatedIncomeTransactions->map(function ($item) {
            $description = '';
            if ($item->source_type === 'invoice_payment') {
                $description = 'Invoice Payment (Invoice ID: ' . ($item->invoice_id ?? 'N/A') . ')';
            } else { // 'deposit'
                $description = $item->note ?? 'Deposit';
            }

            return [
                'date' => Carbon::parse($item->created_at)->format('Y-m-d'),
                'amount' => 'BDT ' . number_format($item->amount, 2),
                'account' => $item->account->name ?? 'N/A',
                'description' => $description,
            ];
        })->values(); // Reset keys after mapping

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }


    /**
     * Display the Expense Report (initial page load).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function expense(Request $request)
    {
        // Default date range: current month
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Load account names for display in the report
        $accounts = Account::all()->keyBy('id');

        return view('accounting::report.expense', compact(
            'startDate',
            'endDate',
            'accounts'
        ));
    }

    /**
     * Get Expense Data for DataTables Server-Side Processing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExpenseData(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');
        $columns = $request->input('columns');

        // Get filter dates from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $carbonStartDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
        $carbonEndDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfMonth()->endOfDay();

        $expensesQuery = AccountTransaction::where('type', 'expense')
            ->whereBetween('created_at', [$carbonStartDate, $carbonEndDate]);

        // Clone query for filtered count
        $expensesFilteredQuery = clone $expensesQuery;

        $totalRecords = AccountTransaction::where('type', 'expense')->count();

        if (!empty($searchValue)) {
            $expensesQuery->where(function ($query) use ($searchValue) {
                $query->where('note', 'like', '%' . $searchValue . '%')
                    ->orWhere('amount', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('account', function ($q) use ($searchValue) {
                        $q->where('name', 'like', '%' . $searchValue . '%');
                    });
            });
        }

        $recordsFiltered = $expensesQuery->count();

        // Apply ordering
        if (isset($columns[$orderColumnIndex])) {
            $orderColumnName = $columns[$orderColumnIndex]['data'];
            if ($orderColumnName === 'date') {
                $expensesQuery->orderBy('created_at', $orderDir);
            } else if ($orderColumnName === 'account') {
                $expensesQuery->join('accounts', 'account_transactions.account_id', '=', 'accounts.id')
                    ->orderBy('accounts.name', $orderDir)
                    ->select('account_transactions.*'); // Select back all account_transactions columns
            } else {
                $expensesQuery->orderBy($orderColumnName, $orderDir);
            }
        }

        // Apply pagination
        $expenses = $expensesQuery->skip($start)
            ->take($length)
            ->with('account')
            ->get();

        $data = $expenses->map(function ($item) {
            return [
                'date' => Carbon::parse($item->created_at)->format('Y-m-d'),
                'amount' => 'BDT ' . number_format($item->amount, 2),
                'account' => $item->account->name ?? 'N/A',
                'description' => $item->note ?? 'Expense',
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
