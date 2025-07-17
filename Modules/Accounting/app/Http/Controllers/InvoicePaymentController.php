<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountTransaction;
use Modules\Accounting\Models\Customer;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\InvoicePayment;
use Yajra\DataTables\Facades\DataTables;

class InvoicePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = InvoicePayment::with(['invoice.customer', 'account'])->select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', function (InvoicePayment $payment) {
                    return $payment->invoice->customer->name ?? 'Advance Payment'; // If invoice_id is null, it's an advance
                })
                ->addColumn('invoice_number', function (InvoicePayment $payment) {
                    return $payment->invoice->invoice_number ?? 'N/A';
                })
                ->addColumn('account_name', function (InvoicePayment $payment) {
                    return $payment->account->name ?? 'N/A';
                })
                ->editColumn('amount', function (InvoicePayment $payment) {
                    return number_format($payment->amount, 2);
                })
                ->editColumn('payment_type', function (InvoicePayment $payment) {
                    return ucwords(str_replace('_', ' ', $payment->payment_type));
                })
                ->editColumn('created_at', function (InvoicePayment $payment) {
                    return \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d H:i:s');
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.invoice_payments.edit', $row->id);
                    $deleteUrl = route('admin.invoice_payments.destroy', $row->id);

                    return '
                        <a href="' . $editUrl . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-sm delete-invoice-payment" onclick="deleteData(' . $row->id . ')"><i class="fas fa-trash"></i></button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('accounting::invoice_payments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $accounts = Account::all();
        return view('accounting::invoice_payments.create', compact('customers', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'account_id' => 'required|exists:accounts,id',
            'method' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'invoice_ids' => 'nullable|array',
            'invoice_ids.*' => 'exists:invoices,id',
        ]);

        DB::transaction(function () use ($validatedData, $request) {
            $paymentAmount = $validatedData['amount'];
            $remainingPayment = $paymentAmount;
            $customer = Customer::find($validatedData['customer_id']);
            $paymentAccount = Account::find($validatedData['account_id']);

            // 1. Apply payment to selected invoices first
            if (!empty($validatedData['invoice_ids'])) {
                $invoicesToPay = Invoice::whereIn('id', $validatedData['invoice_ids'])
                    ->where('customer_id', $customer->id)
                    ->orderBy('invoice_date', 'asc') // Pay oldest first
                    ->get();

                foreach ($invoicesToPay as $invoice) {
                    if ($remainingPayment <= 0) break; // No more payment left

                    $due = $invoice->amount_due;
                    $amountToApplyToInvoice = min($remainingPayment, $due);

                    if ($amountToApplyToInvoice > 0) {
                        InvoicePayment::create([
                            'invoice_id' => $invoice->id,
                            'account_id' => $validatedData['account_id'],
                            'amount' => $amountToApplyToInvoice,
                            'payment_type' => 'invoice_payment',
                            'method' => $validatedData['method'],
                            'note' => $validatedData['note'],
                        ]);
                        $remainingPayment -= $amountToApplyToInvoice;
                    }
                }
            }

            // 2. If there's any remaining payment, record it as an advance
            if ($remainingPayment > 0) {
                InvoicePayment::create([
                    'invoice_id' => null, // No specific invoice for advance
                    'account_id' => $validatedData['account_id'],
                    'amount' => $remainingPayment,
                    'payment_type' => 'advance',
                    'method' => $validatedData['method'],
                    'note' => 'Advance payment from customer ' . $customer->name . '. ' . ($validatedData['note'] ?? ''),
                ]);
            }

            // 3. Record a single account transaction for the total payment received
            AccountTransaction::create([
                'account_id' => $validatedData['account_id'],
                'type' => 'invoice_payment',
                'amount' => $paymentAmount,
                'reference' => 'Payment from ' . $customer->name,
                'note' => $validatedData['note'],
            ]);

            // 4. Update the account balance (increase)
            $paymentAccount->increment('balance', $paymentAmount);
        });
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('accounting::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $payment = InvoicePayment::findOrFail($id);
        $customers = Customer::all();
        $accounts = Account::all();
        return view('accounting::invoice_payments.create', compact('payment', 'customers', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id', // Customer ID is needed for context but not directly updated on InvoicePayment
            'amount' => 'required|numeric|min:0.01',
            'account_id' => 'required|exists:accounts,id',
            'method' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            // invoice_ids are not directly updated on an existing InvoicePayment record if it's a single entry.
            // If you allow changing which invoices a payment applies to, this logic becomes much more complex.
        ]);

        $invoicePayment = InvoicePayment::findOrFail($id);
        DB::transaction(function () use ($validatedData, $invoicePayment) {
            $oldAmount = $invoicePayment->amount;
            $oldAccountId = $invoicePayment->account_id;

            // Revert old transaction and account balance
            $oldAccount = Account::find($oldAccountId);
            $oldAccount->decrement('balance', $oldAmount);

            // Find and update the corresponding AccountTransaction (if you track them one-to-one)
            // This assumes a simple update where the original transaction is modified.
            // For more robust systems, you might create new debit/credit entries to adjust.
            $accountTransaction = AccountTransaction::where('account_id', $oldAccountId)
                ->where('amount', $oldAmount)
                ->where('type', $invoicePayment->payment_type == 'invoice_payment' ? 'invoice_payment' : 'advance_payment')
                ->latest() // Get the most recent one
                ->first();

            if ($accountTransaction) {
                $accountTransaction->update([
                    'amount' => $validatedData['amount'],
                    'account_id' => $validatedData['account_id'],
                    'note' => $validatedData['note'],
                ]);
            } else {
                // If no matching transaction found (e.g., if initial transaction was grouped), create a new one
                AccountTransaction::create([
                    'account_id' => $validatedData['account_id'],
                    'type' => $invoicePayment->payment_type == 'invoice_payment' ? 'invoice_payment' : 'advance_payment',
                    'amount' => $validatedData['amount'],
                    'reference' => 'Payment adjustment for ' . ($invoicePayment->invoice->customer->name ?? 'N/A'),
                    'note' => $validatedData['note'],
                ]);
            }


            // Update the InvoicePayment record
            $invoicePayment->update([
                'account_id' => $validatedData['account_id'],
                'amount' => $validatedData['amount'],
                'method' => $validatedData['method'],
                'note' => $validatedData['note'],
                // payment_type and invoice_id are usually fixed after creation for a specific payment record.
                // If you need to change these, it's a much more involved process.
            ]);

            // Apply new transaction and account balance
            $newAccount = Account::find($validatedData['account_id']);
            $newAccount->increment('balance', $validatedData['amount']);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $invoicePayment = AccountTransaction::findOrFail($id);
        DB::transaction(function () use ($invoicePayment) {
            $paymentAccount = Account::find($invoicePayment->account_id);

            // Decrease balance from the account
            $paymentAccount->decrement('balance', $invoicePayment->amount);

            // Optionally, find and delete/adjust the corresponding AccountTransaction
            $accountTransaction = AccountTransaction::where('account_id', $invoicePayment->account_id)
                ->where('amount', $invoicePayment->amount)
                ->where('type', $invoicePayment->payment_type == 'invoice_payment' ? 'invoice_payment' : 'advance_payment')
                ->latest()
                ->first();
            if ($accountTransaction) {
                $accountTransaction->delete();
            }

            $invoicePayment->delete();
        });
    }
}
