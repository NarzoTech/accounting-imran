<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountTransaction;
use Modules\Accounting\Models\Customer;
use Modules\Accounting\Models\CustomerAdvance;
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
        $method = $request->input('method');
        $note = $request->input('note');


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

                            AccountTransaction::create([
                                'account_id' => $accountId,
                                'type'       => 'invoice_payment',
                                'amount'     => $amountToApply,
                                'reference'  => 'Invoice #' . $invoice->invoice_number,
                                'note'       => 'Due Payment applied to invoice.',
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

                $customer->advances()->create([
                    'amount' => $remainingAmount,
                    'type' => 'received',
                    'account_id' => $accountId,
                    'note' => $note ? $note . ' (Advance Payment)' : 'Advance Payment',
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
    public function update(Request $request, InvoicePayment $payment)
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
        $method = $request->input('method');
        $note = $request->input('note');

        DB::beginTransaction();

        try {
            // 1. Reverse previous payments related to this InvoicePayment (assumes you track them)
            $relatedPayments = InvoicePayment::where('group_id', $payment->group_id)->get(); // assuming group_id groups a payment set

            foreach ($relatedPayments as $relPayment) {
                // Delete related AccountTransaction
                AccountTransaction::where([
                    'account_id' => $relPayment->account_id,
                    'amount' => $relPayment->amount,
                    'reference' => 'Invoice #' . $relPayment->invoice->invoice_number ?? '',
                ])->delete();

                $relPayment->delete();
            }

            // 2. Remove any associated CustomerAdvance if created during previous update
            CustomerAdvance::where([
                'customer_id' => $customerId,
                'type' => 'received',
                'note' => 'Advance Payment',
            ])->delete();

            // 3. Reapply payments based on new input
            $totalApplied = 0;
            $groupId = uniqid('pay_'); // new group ID for this update set

            foreach ($appliedAmounts as $invoiceId => $amount) {
                $amount = (float) $amount;
                if ($amount > 0) {
                    $invoice = Invoice::find($invoiceId);
                    if ($invoice) {
                        $dueBeforePayment = $invoice->amount_due;
                        $amountToApply = min($amount, $dueBeforePayment);

                        if ($amountToApply > 0) {
                            $invoice->payments()->create([
                                'group_id'     => $groupId,
                                'account_id'   => $accountId,
                                'amount'       => $amountToApply,
                                'payment_type' => 'invoice_payment',
                                'method'       => $method,
                                'note'         => $note,
                                'payment_date' => $paymentDate,
                            ]);

                            AccountTransaction::create([
                                'account_id' => $accountId,
                                'type'       => 'invoice_payment',
                                'amount'     => $amountToApply,
                                'reference'  => 'Invoice #' . $invoice->invoice_number,
                                'note'       => 'Due Payment applied to invoice.',
                                'transaction_date' => $paymentDate,
                            ]);

                            $totalApplied += $amountToApply;
                        }
                    }
                }
            }

            // 4. Handle remaining as advance (if any)
            $remainingAmount = $receivingAmount - $totalApplied;

            if ($remainingAmount > 0) {
                $customer = Customer::findOrFail($customerId);

                $customer->advances()->create([
                    'amount'      => $remainingAmount,
                    'type'        => 'received',
                    'account_id'  => $accountId,
                    'note'        => $note ? $note . ' (Advance Payment)' : 'Advance Payment',
                    'created_at'  => $paymentDate,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.customer.index')->with([
                'message'    => __('Payment updated successfully!'),
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with([
                'message'    => __('Failed to update payment: ') . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
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
