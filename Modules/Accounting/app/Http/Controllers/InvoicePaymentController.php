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
                    return $payment->invoice->customer->name ?? 'Advance Payment';
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
    public function create(Request $request)
    {
        // Ensure a customer_id is provided
        if (!$request->has('customer_id')) {
            return back()->with([
                'message'    => __('Please select a customer first.'),
                'alert-type' => 'warning'
            ]);
        }

        $customer = Customer::find($request->get('customer_id'));

        // Handle case where customer is not found
        if (!$customer) {
            return back()->with([
                'message'    => __('Customer not found.'),
                'alert-type' => 'error'
            ]);
        }

        $accounts = Account::all();

        // Fetch all outstanding invoices for the customer
        // An invoice is 'outstanding' if its total_amount is greater than the sum of its associated invoice payments.
        $invoices = Invoice::where('customer_id', $customer->id)
            ->where(function ($query) {
                $query->whereRaw('invoices.total_amount > (SELECT COALESCE(SUM(amount), 0) FROM invoice_payments WHERE invoice_payments.invoice_id = invoices.id AND invoice_payments.payment_type = "invoice_payment")');
            })
            ->orderBy('invoice_date', 'asc') // Order by date for sequential allocation
            ->get();

        // Calculate the total due amount for all outstanding invoices
        $totalDue = $invoices->sum('amount_due'); // amount_due is an accessor in your Invoice model

        return view('accounting::invoice_payments.create', compact('customer', 'accounts', 'invoices', 'totalDue'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'receiving_amount'  => 'required|numeric|min:0.01',
            'account_id'        => 'required|exists:accounts,id',
            'payment_date'      => 'required|date_format:d-m-Y',
            'amounts'           => 'nullable|array',
            'amounts.*'         => 'numeric|min:0',
        ]);

        $customerId = $request->input('customer_id');
        $receivingAmount = (float) $request->input('receiving_amount');
        $accountId = $request->input('account_id');
        $paymentDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->input('payment_date'))->format('Y-m-d');
        $appliedAmounts = $request->input('amounts', []);
        $method = $request->input('method'); // assuming 'method' input is also present as in your original form
        $note = $request->input('note'); // assuming 'note' input is also present


        DB::beginTransaction();
        try {
            $totalApplied = 0;

            // Iterate through the amounts provided for each invoice
            foreach ($appliedAmounts as $invoiceId => $amount) {
                $amount = (float) $amount;
                if ($amount > 0) {
                    $invoice = Invoice::find($invoiceId);
                    if ($invoice) {
                        // Ensure the applied amount does not exceed the invoice's current due
                        $dueBeforePayment = $invoice->amount_due;
                        $amountToApply = min($amount, $dueBeforePayment);

                        if ($amountToApply > 0) {
                            $invoice->payments()->create([
                                'account_id'   => $accountId,
                                'amount'       => $amountToApply,
                                'payment_type' => 'invoice_payment',
                                'method'       => $method,
                                'note'         => $note,

                            ]);

                            $totalApplied += $amountToApply;
                        }
                    }
                }
            }

            // Handle any remaining amount as an advance payment if totalApplied is less than receivingAmount
            $remainingAmount = $receivingAmount - $totalApplied;
            if ($remainingAmount > 0) {

                $customer = Customer::findOrFail($customerId);
                $customer->payments()->create([
                    'account_id'   => $accountId,
                    'amount'       => $remainingAmount,
                    'payment_type' => 'advance', // Or 'advance_payment' as per your enum
                    'method'       => $method,
                    'note'         => $note ? $note . ' (Advance Payment)' : 'Advance Payment',
                ]);
            }

            DB::commit();
            return redirect()->route('admin.customer.index')->with([
                'message'    => __('Payment recorded successfully!'),
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with([
                'message'    => __('Failed to record payment: ') . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
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
