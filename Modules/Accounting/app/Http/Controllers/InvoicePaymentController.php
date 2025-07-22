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

            $data = InvoicePayment::with(['invoice.customer', 'account'])
                ->whereHas('invoice', function ($query) use ($request) {
                    $query->where('id', $request->invoice_id);
                })
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('customer_name', function (InvoicePayment $payment) {
                    return optional(optional($payment->invoice)->customer)->name ?? 'Advance Payment';
                })

                ->addColumn('invoice_number', function (InvoicePayment $payment) {
                    return optional($payment->invoice)->invoice_number ?? 'N/A';
                })

                ->addColumn('account_name', function (InvoicePayment $payment) {
                    return optional($payment->account)->name ?? 'N/A';
                })

                ->editColumn('amount', function (InvoicePayment $payment) {
                    return number_format($payment->amount ?? 0, 2);
                })

                ->editColumn('payment_type', function (InvoicePayment $payment) {
                    return ucwords(str_replace('_', ' ', $payment->payment_type ?? ''));
                })

                ->editColumn('created_at', function (InvoicePayment $payment) {
                    return optional($payment->created_at)->format('Y-m-d H:i:s') ?? 'N/A';
                })

                ->addColumn('actions', function (InvoicePayment $payment) {
                    $editUrl = route('admin.invoice_payments.edit', ['invoice_payment' => $payment->id, 'customer_id' => $payment->invoice->customer_id ?? null]);
                    return '
                <a href="' . $editUrl . '" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-danger btn-sm delete-invoice-payment" onclick="deleteData(' . $payment->id . ')">
                    <i class="fas fa-trash"></i>
                </button>
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
                $query->whereRaw('invoices.total_amount > (
            SELECT COALESCE(SUM(amount+ discount), 0)
            FROM invoice_payments
            WHERE invoice_payments.invoice_id = invoices.id
              AND invoice_payments.payment_type IN ("invoice_payment")
        )');
            })
            ->when($request->invoice_id, function ($query) use ($request) {
                $query->where('id', $request->invoice_id);
            })
            ->orderBy('invoice_date', 'asc')
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
            'discount'          => 'nullable|numeric|min:0',
        ]);

        $customerId = $request->input('customer_id');
        $receivingAmount = (float) $request->input('receiving_amount');
        $accountId = $request->input('account_id');
        $paymentDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->input('payment_date'))->format('Y-m-d');
        $appliedAmounts = $request->input('amounts', []);
        $method = $request->input('method');
        $note = $request->input('note');
        $discount = (float) $request->input('payment_discount', 0);


        DB::beginTransaction();
        try {
            $totalApplied = 0;

            $groupId = uniqid('pay_');

            // Iterate through the amounts provided for each invoice
            foreach ($appliedAmounts as $invoiceId => $amount) {
                $amount = (float) $amount;
                if ($amount > 0) {
                    $invoice = Invoice::find($invoiceId);
                    if ($invoice) {
                        // Ensure the applied amount does not exceed the invoice's current due
                        $dueBeforePayment = $invoice->amount_due;
                        $amountToApply = min($amount, $dueBeforePayment);

                        if ($request->payment_discount > 0 && $dueBeforePayment == $amountToApply + $request->payment_discount) {
                            $discount = $request->payment_discount;
                        } else {
                            $discount = 0;
                        }

                        if ($amountToApply > 0) {
                            $invoice->payments()->create([
                                'group_id'     => $groupId,
                                'account_id'   => $accountId,
                                'amount'       => $amountToApply,
                                'discount'     => $discount,
                                'payment_type' => 'invoice_payment',
                                'method'       => $method,
                                'note'         => $note,
                            ]);

                            AccountTransaction::create([
                                'account_id' => $accountId,
                                'type'       => 'invoice_payment',
                                'amount'     => $amountToApply + $discount,
                                'reference'  => 'Invoice #' . $invoice->invoice_number,
                                'note'       => 'Due Payment applied to invoice.',
                                'group'     => $groupId,
                            ]);

                            $totalApplied += $amountToApply;
                        }
                    }
                }
            }

            if ($discount > 0) {
                AccountTransaction::create([
                    'account_id' => $accountId,
                    'type'       => 'discount',
                    'amount'     => -$discount,
                    'reference'  => 'Customer Discount',
                    'note'       => 'Discount applied on payment.',
                    'group'     => $groupId,
                ]);

                $totalApplied += $discount;
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
                    'group_id' => $groupId,
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
    public function edit(Request $request, $id)
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

        $payment = InvoicePayment::findOrFail($id); // This is one payment record from the group
        $accounts = Account::all();

        // Get all invoice IDs that were part of this payment group
        $invoiceIdsInGroup = InvoicePayment::where('group_id', $payment->group_id)
            ->pluck('invoice_id')
            ->toArray();

        // Fetch all invoices for the customer that are either outstanding OR were part of this payment group
        $invoices = Invoice::where('customer_id', $customer->id)
            ->with('payments')
            ->where(function ($query) use ($invoiceIdsInGroup, $payment) {
                // Invoices that are currently outstanding (excluding amounts from THIS payment group)
                $query->whereRaw('invoices.total_amount > (
                    SELECT COALESCE(SUM(amount + discount), 0)
                    FROM invoice_payments
                    WHERE invoice_payments.invoice_id = invoices.id
                      AND invoice_payments.payment_type IN ("invoice_payment")
                      AND invoice_payments.group_id != ?
                )', [$payment->group_id])
                    // OR invoices that were part of this payment group (to display them in the form)
                    ->orWhereIn('id', $invoiceIdsInGroup);
            })
            ->orderBy('invoice_date', 'asc')
            ->get();

        // Calculate the total due amount for all invoices displayed
        $totalDue = $invoices->sum('amount_due');

        // Prepare applied amounts for the view (for pre-filling individual invoice inputs)
        // This will be a map of invoice_id => amount applied by THIS group_id
        $appliedAmounts = InvoicePayment::where('group_id', $payment->group_id)
            ->pluck('amount', 'invoice_id')
            ->toArray();

        // Get the total discount for the entire group
        $totalDiscountForGroup = InvoicePayment::where('group_id', $payment->group_id)->sum('discount');

        // Calculate the total amount applied to invoices within this group (excluding the overall discount)
        $totalAppliedToInvoices = InvoicePayment::where('group_id', $payment->group_id)->sum('amount');


        return view('accounting::invoice_payments.edit', compact(
            'payment',
            'customer',
            'accounts',
            'invoices', // <-- This was missing!
            'totalDue',
            'appliedAmounts',
            'totalDiscountForGroup',
            'totalAppliedToInvoices'
        ));
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
            'payment_discount'  => 'nullable|numeric|min:0',

        ]);

        $customerId = $request->input('customer_id');
        $receivingAmount = (float) $request->input('receiving_amount');
        $accountId = $request->input('account_id');
        $paymentDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->input('payment_date'))->format('Y-m-d');
        $appliedAmounts = $request->input('amounts', []);
        $method = $request->input('method');
        $note = $request->input('note');
        $inputDiscount = (float) $request->input('payment_discount', 0);



        DB::beginTransaction();

        try {
            $paymentGroup = InvoicePayment::findOrFail($payment->id);

            $groupId = $paymentGroup->group_id;


            // ✅ Delete all existing payments and transactions for this group
            InvoicePayment::where('group_id', $groupId)->delete();
            AccountTransaction::where('group', $groupId)->delete();

            $totalApplied = 0;
            $usedDiscount = 0;

            foreach ($appliedAmounts as $invoiceId => $amount) {
                $amount = (float) $amount;
                if ($amount > 0) {
                    $invoice = Invoice::find($invoiceId);

                    if ($invoice) {
                        $dueBeforePayment = $invoice->amount_due;
                        $amountToApply = min($amount, $dueBeforePayment);

                        // Apply discount only if this invoice exactly matches due+discount
                        $discount = 0;
                        if ($inputDiscount > 0 && $dueBeforePayment == $amountToApply + $inputDiscount) {
                            $discount = $inputDiscount;
                            $usedDiscount = $discount;
                        }

                        // 💸 Save invoice payment
                        $invoice->payments()->create([
                            'group_id'     => $groupId,
                            'account_id'   => $accountId,
                            'amount'       => $amountToApply,
                            'discount'     => $discount,
                            'payment_type' => 'invoice_payment',
                            'method'       => $method,
                            'note'         => $note,
                            'created_at'   => $paymentDate,
                            'updated_at'   => now(),
                        ]);

                        // 💳 Log account transaction
                        AccountTransaction::create([
                            'account_id' => $accountId,
                            'type'       => 'invoice_payment',
                            'amount'     => $amountToApply + $discount,
                            'reference'  => "Invoice #{$invoice->invoice_number}",
                            'note'       => 'Updated due payment applied to invoice.',
                            'group'      => $groupId,
                        ]);

                        $totalApplied += $amountToApply;
                    }
                }
            }

            if ($usedDiscount > 0) {
                AccountTransaction::create([
                    'account_id' => $accountId,
                    'type'       => 'discount',
                    'amount'     => -$usedDiscount,
                    'reference'  => "Customer Discount",
                    'note'       => 'Updated discount applied on payment.',
                    'group'      => $groupId,
                ]);

                $totalApplied += $usedDiscount;
            }


            // Handle any remaining amount as an advance payment if totalApplied is less than receivingAmount
            $remainingAmount = $receivingAmount - $totalApplied;
            if ($remainingAmount > 0) {
                $customer = Customer::findOrFail($customerId);

                $customer->advances()->create([
                    'amount'      => $remainingAmount,
                    'type'        => 'received',
                    'account_id'  => $accountId,
                    'note'        => $note ? $note . ' (Advance Payment)' : 'Advance Payment',
                    'created_at'  => $paymentDate,
                    'updated_at'  => now(),
                    'group_id'    => $groupId,
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
    public function destroy(InvoicePayment $payment)
    {
        DB::beginTransaction();

        try {
            $groupId = $payment->group_id;

            // Delete all invoice payments in the group
            InvoicePayment::where('group_id', $groupId)->delete();

            // Delete all related account transactions
            AccountTransaction::where('group', $groupId)->delete();

            // Optionally delete customer advances related to this group
            CustomerAdvance::where('group_id', $groupId)->delete();

            DB::commit();

            return redirect()->route('admin.customer.index')->with([
                'message'    => __('Payment deleted successfully!'),
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with([
                'message'    => __('Failed to delete payment: ') . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }
}
