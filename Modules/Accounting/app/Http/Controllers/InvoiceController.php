<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountTransaction;
use Modules\Accounting\Models\Container;
use Modules\Accounting\Models\Customer;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\Product;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Invoice::with('customer'); // Eager load customer for display

            // Apply filters based on request
            if ($request->has('status') && $request->status !== 'all') {
                $status = $request->status;
                $query->where(function ($q) use ($status) {
                    if ($status === 'unpaid') {
                        $q->has('payments', '=', 0) // No payments
                            ->orWhereDoesntHave('payments'); // Or no payments linked
                    } elseif ($status === 'partial') {
                        // Find invoices where total_amount > sum of payments and sum of payments > 0
                        $q->whereRaw('total_amount > (SELECT SUM(amount) FROM invoice_payments WHERE invoice_id = id AND payment_type = "invoice_payment")')
                            ->whereRaw('(SELECT SUM(amount) FROM invoice_payments WHERE invoice_id = id AND payment_type = "invoice_payment") > 0');
                    } elseif ($status === 'paid') {
                        // Find invoices where total_amount <= sum of payments
                        $q->whereRaw('total_amount <= (SELECT SUM(amount) FROM invoice_payments WHERE invoice_id = invoices.id AND payment_type = "invoice_payment")');
                    } elseif ($status === 'overdue') {
                        // Overdue: payment_date is in the past AND not fully paid
                        $q->where('payment_date', '<', Carbon::today())
                            ->whereRaw('invoices.total_amount > (SELECT COALESCE(SUM(amount), 0) FROM invoice_payments WHERE invoice_id = invoices.id AND payment_type = "invoice_payment")');
                    }
                });
            }

            if ($request->has('customer_id') && $request->customer_id) {
                $query->where('customer_id', $request->customer_id);
            }

            return DataTables::of($query->select('invoices.*')) // Select invoices.* to avoid ambiguity with joined columns
                ->addIndexColumn()
                ->addColumn('customer_name', function (Invoice $invoice) {
                    return $invoice->customer->name ?? 'N/A';
                })
                ->editColumn('invoice_date', function (Invoice $invoice) {
                    return Carbon::parse($invoice->invoice_date)->format('Y-m-d');
                })
                ->editColumn('payment_date', function (Invoice $invoice) {
                    return Carbon::parse($invoice->payment_date)->format('Y-m-d');
                })
                ->editColumn('total_amount', function (Invoice $invoice) {
                    return number_format($invoice->total_amount, 2);
                })
                ->addColumn('amount_paid', function (Invoice $invoice) {
                    return number_format($invoice->amount_paid, 2);
                })
                ->addColumn('amount_due', function (Invoice $invoice) {
                    return number_format($invoice->amount_due, 2);
                })
                ->addColumn('payment_status_display', function (Invoice $invoice) {
                    $status = $invoice->payment_status;
                    $class = '';
                    if ($status === 'Paid') {
                        $class = 'badge bg-success';
                    } elseif ($status === 'Partially Paid') {
                        $class = 'badge bg-warning text-dark';
                    } else {
                        $class = 'badge bg-danger';
                    }
                    if ($invoice->is_overdue && $status !== 'Paid') {
                        $class .= ' bg-danger'; // Add overdue styling
                        $status .= ' (Overdue)';
                    }
                    return '<span class="' . $class . '">' . $status . '</span>';
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.invoice.edit', $row->id);
                    $detailsUrl = route('admin.invoice.show', $row->id);
                    $makePaymentUrl = route('admin.invoice_payments.create', ['invoice_id' => $row->id, 'customer_id' => $row->customer_id]);
                    return '
                        <div class="btn-group" role="group" aria-label="Invoice Actions">
                            <a href="' . $detailsUrl . '" class="btn btn-info btn-sm" title="View Details"><i class="fas fa-eye"></i></a>
                            <a href="' . $editUrl . '" class="btn btn-primary btn-sm" title="Edit Invoice"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-danger btn-sm delete-invoice" onclick="deleteData(' . $row->id . ')" title="Delete Invoice"><i class="fas fa-trash"></i></button>
                            <a href="' . $makePaymentUrl . '" class="btn btn-success btn-sm" title="Make Payment"><i class="fas fa-dollar-sign"></i> Pay</a>
                        </div>
                    ';
                })
                ->rawColumns(['payment_status_display', 'actions'])
                ->make(true);
        }

        $customers = Customer::all();
        return view('accounting::invoice.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $containers = Container::where('status', 'Arrived')->get();
        $products = Product::where('status', 1)->get();
        $customers = Customer::get();
        $accounts = Account::all();
        return view('accounting::invoice.create', compact('containers', 'products', 'customers', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'customer_id'         => 'required|exists:customers,id',
            'invoice_number'      => 'required|string|max:255|unique:invoices,invoice_number',
            'po_so_number'        => 'nullable|string|max:255',
            'invoice_date'        => 'required|date',
            'payment_date'        => 'required|date|after_or_equal:invoice_date',
            'container_id'        => 'nullable|exists:containers,id',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'delivery_charge'     => 'nullable|numeric|min:0',
            'notes_terms'         => 'nullable|string',
            'invoice_footer'      => 'nullable|string',
            'items'               => 'required|array|min:1',
            'items.*.product_id'  => 'nullable|exists:products,id', // Can be null if new product
            'items.*.description' => 'nullable|string',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit'        => 'nullable|string|max:30',
            'items.*.price'       => 'required|numeric|min:0',
            'items.*.amount'      => 'required|numeric|min:0',

            'payment_status_input' => 'required|in:unpaid,paid',
            'payment_account_id'   => 'nullable|required_if:payment_status_input,paid|exists:accounts,id',
            'amount_paid'          => 'nullable|numeric|min:0', // This is the amount paid during creation
        ]);

        DB::transaction(function () use ($request, $validatedData) {
            // Calculate subtotal and total amount based on items
            $subtotal = 0;
            foreach ($validatedData['items'] as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }

            $discountAmount = ($subtotal * ($validatedData['discount_percentage'] ?? 0) / 100);
            $deliveryCharge = $validatedData['delivery_charge'] ?? 0;
            $totalAmount = $subtotal - $discountAmount + $deliveryCharge;

            $invoice = Invoice::create([
                'customer_id'         => $validatedData['customer_id'],
                'invoice_number'      => $validatedData['invoice_number'],
                'po_so_number'        => $validatedData['po_so_number'],
                'invoice_date'        => $validatedData['invoice_date'],
                'payment_date'        => $validatedData['payment_date'],
                'container_id'        => $validatedData['container_id'],
                'discount_percentage' => $validatedData['discount_percentage'] ?? 0,
                'delivery_charge'     => $deliveryCharge,
                'subtotal'            => $subtotal,
                'total_amount'        => $totalAmount,
                'notes_terms'         => $validatedData['notes_terms'],
                'invoice_footer'      => $validatedData['invoice_footer'],
            ]);

            // Save invoice items
            foreach ($validatedData['items'] as $itemData) {
                $invoice->items()->create($itemData);
            }

            // Handle payment if status is 'paid'
            if ($validatedData['payment_status_input'] === 'paid' && ($validatedData['amount_paid'] ?? 0) > 0) {
                $paymentAmount = $validatedData['amount_paid'];
                $paymentAccountId = $validatedData['payment_account_id'];

                // Create InvoicePayment record
                $invoice->payments()->create([
                    'account_id'   => $paymentAccountId,
                    'amount'       => $paymentAmount,
                    'payment_type' => 'invoice_payment', // Always invoice_payment for creation
                    'method'       => $request->input('payment_method_text'), // Get method from a text input
                    'note'         => 'Payment received during invoice creation.',
                ]);

                // Update Account balance
                $account = Account::find($paymentAccountId);
                $account->increment('balance', $paymentAmount);

                // Record AccountTransaction
                AccountTransaction::create([ // Ensure correct namespace for AccountTransaction
                    'account_id' => $paymentAccountId,
                    'type'       => 'invoice_payment',
                    'amount'     => $paymentAmount,
                    'reference'  => 'Invoice #' . $invoice->invoice_number,
                    'note'       => 'Payment received for invoice creation.',
                ]);
            }
        });


        $notification = [
            'message' => 'Invoice created successfully.',
            'alert-type' => 'success',
        ];

        return redirect()->route('admin.invoice.index')->with($notification);
    }

    /**
     * Show the specified resource.
     */
    public function show(Invoice $invoice)
    {
        // Eager load necessary relationships for the view
        $invoice->load(['customer', 'items.product', 'payments.account']); // Assuming InvoiceItem has a 'product' relationship

        // Pass the invoice data to the view
        return view('accounting::invoice.show', compact('invoice'));
    }

    public function download($id)
    {

        $invoice = Invoice::findOrFail($id);
        // Eager load necessary relationships for the PDF view
        $invoice->load(['customer', 'items.product', 'payments.account']);

        // Load the same view used for display, but for PDF generation
        $pdf = Pdf::loadView('accounting::invoice.pdf_template', compact('invoice'));

        // You might want a separate, simpler template for PDF to ensure consistent rendering
        // For now, we'll assume 'accounting::invoice.pdf_template' exists and is optimized for PDF.
        // If not, you can use the same 'show' view and adjust its CSS for print media.
        // Example: $pdf = PDF::loadView('accounting::invoice.show', compact('invoice'));

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        // Eager load relationships needed for pre-filling the form
        $invoice->load(['customer', 'items.product', 'container', 'payments']);

        $customers = Customer::all(); // Fetch all customers for the modal
        $products = Product::all();   // Fetch all products for the modal
        $containers = Container::all(); // Fetch all containers
        $accounts = Account::all();   // Fetch all accounts for payment section

        // Determine initial payment status and amount paid for the edit view
        $initialPaymentStatus = $invoice->payment_status; // 'Paid', 'Partially Paid', 'Unpaid'
        $initialAmountPaid = $invoice->amount_paid;

        return view('accounting::invoice.edit', compact(
            'invoice',
            'customers',
            'products',
            'containers',
            'accounts',
            'initialPaymentStatus',
            'initialAmountPaid'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        // Validation rules (adjust as per your exact requirements)
        $validatedData = $request->validate([
            'customer_id'         => 'required|exists:customers,id',
            'invoice_number'      => 'required|string|max:255|unique:invoices,invoice_number,' . $invoice->id, // Unique except for self
            'po_so_number'        => 'nullable|string|max:255',
            'invoice_date'        => 'required|date',
            'payment_date'        => 'required|date|after_or_equal:invoice_date',
            'container_id'        => 'nullable|exists:containers,id',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'delivery_charge'     => 'nullable|numeric|min:0',
            'notes_terms'         => 'nullable|string',
            'invoice_footer'      => 'nullable|string',
            'items'               => 'required|array|min:1',
            'items.*.item_id'     => 'nullable|exists:invoice_items,id', // Existing item ID
            'items.*.product_id'  => 'nullable|exists:products,id',
            'items.*.description' => 'nullable|string',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit'        => 'nullable|string|max:30',
            'items.*.price'       => 'required|numeric|min:0',
            'items.*.amount'      => 'required|numeric|min:0',
            'items_to_delete'     => 'nullable|array', // IDs of items to delete
            'items_to_delete.*'   => 'exists:invoice_items,id',

            'payment_status_input' => 'required|in:unpaid,paid',
            'payment_account_id'   => 'nullable|required_if:payment_status_input,paid|exists:accounts,id',
            'amount_paid' => 'nullable|numeric|min:0', // This is amount paid on THIS update
        ]);

        DB::transaction(function () use ($request, $validatedData, $invoice) {
            // Calculate subtotal and total amount based on items
            $subtotal = 0;
            foreach ($validatedData['items'] as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }

            $discountAmount = ($subtotal * ($validatedData['discount_percentage'] ?? 0) / 100);
            $deliveryCharge = $validatedData['delivery_charge'] ?? 0;
            $totalAmount = $subtotal - $discountAmount + $deliveryCharge;

            // Update invoice core details
            $invoice->update([
                'customer_id'         => $validatedData['customer_id'],
                'invoice_number'      => $validatedData['invoice_number'],
                'po_so_number'        => $validatedData['po_so_number'],
                'invoice_date'        => $validatedData['invoice_date'],
                'payment_date'        => $validatedData['payment_date'],
                'container_id'        => $validatedData['container_id'],
                'discount_percentage' => $validatedData['discount_percentage'] ?? 0,
                'delivery_charge'     => $deliveryCharge,
                'subtotal'            => $subtotal,
                'total_amount'        => $totalAmount,
                'notes_terms'         => $validatedData['notes_terms'],
                'invoice_footer'      => $validatedData['invoice_footer'],
            ]);

            // Handle invoice items update/create/delete
            $existingItemIds = $invoice->items->pluck('id')->toArray();
            $itemsToKeep = [];

            foreach ($validatedData['items'] as $itemData) {
                if (isset($itemData['item_id']) && in_array($itemData['item_id'], $existingItemIds)) {
                    // Update existing item
                    $idToUpdate = $itemData['item_id'];
                    unset($itemData['item_id']);

                    $invoice->items()->where('id', $idToUpdate)->update($itemData);
                    $itemsToKeep[] = $idToUpdate;
                } else {
                    // Create new item
                    $invoice->items()->create($itemData);
                }
            }

            // Delete items not in the submitted list
            $itemsToDelete = array_diff($existingItemIds, $itemsToKeep);
            if (!empty($itemsToDelete)) {
                $invoice->items()->whereIn('id', $itemsToDelete)->delete();
            }

            $currentPaymentStatus = $invoice->payment_status; // Get status BEFORE this update
            $newPaymentStatusInput = $validatedData['payment_status_input'];
            $amountPaidOnUpdate = $validatedData['amount_paid'] ?? 0;

            // If invoice was previously unpaid/partial and is now marked as paid with an amount
            if ($amountPaidOnUpdate > 0) {

                $paymentAccountId = $validatedData['payment_account_id'];

                $invoice->payments()->create([
                    'account_id'   => $paymentAccountId,
                    'amount'       => $amountPaidOnUpdate,
                    'payment_type' => 'invoice_payment',
                    'method'       => $request->input('payment_method_text_update'), // Get method from a text input
                    'note'         => 'Payment received during invoice update.',
                ]);

                // Update Account balance
                $account = Account::find($paymentAccountId);
                $account->increment('balance', $amountPaidOnUpdate);

                // Record AccountTransaction
                AccountTransaction::create([
                    'account_id' => $paymentAccountId,
                    'type'       => 'invoice_payment',
                    'amount'     => $amountPaidOnUpdate,
                    'reference'  => 'Invoice #' . $invoice->invoice_number . ' (Update)',
                    'note'       => 'Payment received for invoice update.',
                ]);
            }
        });

        return redirect()->route('admin.invoice.index')->with([
            'message' => 'Invoice updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        try {
            DB::transaction(function () use ($invoice) {
                // 1. Reverse payments and account transactions associated with this invoice
                foreach ($invoice->payments as $payment) {
                    // Update account balance: subtract the amount that was added
                    $account = $payment->account;
                    if ($account) {
                        $account->decrement('balance', $payment->amount);
                    }

                    // Delete the associated AccountTransaction
                    AccountTransaction::where('account_id', $payment->account_id)
                        ->where('type', 'invoice_payment')
                        ->where('reference', 'LIKE', 'Invoice #' . $invoice->invoice_number . '%')
                        ->where('amount', $payment->amount) // Add amount check for specificity
                        ->delete();

                    // Delete the InvoicePayment record itself
                    $payment->delete();
                }

                $invoice->items()->delete();

                // 3. Delete the invoice itself
                $invoice->delete();
            });

            return redirect()->route('admin.invoice.index')->with([
                'message' => 'Invoice deleted successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error deleting invoice: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete invoice. ' . $e->getMessage());
        }
    }
}
