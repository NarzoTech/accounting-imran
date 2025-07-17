<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                        $q->whereRaw('invoices.total_amount > (SELECT SUM(amount) FROM invoice_payments WHERE invoice_id = invoices.id AND payment_type = "invoice_payment")')
                            ->whereRaw('(SELECT SUM(amount) FROM invoice_payments WHERE invoice_id = invoices.id AND payment_type = "invoice_payment") > 0');
                    } elseif ($status === 'paid') {
                        // Find invoices where total_amount <= sum of payments
                        $q->whereRaw('invoices.total_amount <= (SELECT SUM(amount) FROM invoice_payments WHERE invoice_id = invoices.id AND payment_type = "invoice_payment")');
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
        return view('accounting::invoice.create', compact('containers', 'products', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'payment_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.amount' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'delivery_charge' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_account' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = collect($request->items)->sum('amount');
            $discount = $subtotal * ($request->discount_percentage / 100);
            $total = $subtotal - $discount + $request->delivery_charge;

            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'invoice_number' => $request->invoice_number,
                'po_so_number' => $request->po_so_number,
                'invoice_date' => $request->invoice_date,
                'payment_date' => $request->payment_date,
                'container_id' => $request->container_id,
                'discount_percentage' => $request->discount_percentage,
                'delivery_charge' => $request->delivery_charge,
                'subtotal' => $subtotal,
                'total_amount' => $total,
                'notes_terms' => $request->notes_terms,
                'invoice_footer' => $request->invoice_footer,
            ]);

            foreach ($request->items as $item) {
                $invoice->items()->create($item);
            }

            if ($request->amount_paid > 0) {
                $invoice->payments()->create([
                    'payment_account' => $request->payment_account,
                    'amount_paid' => $request->amount_paid,
                    'payment_date' => $request->payment_date,
                ]);
            }

            DB::commit();

            $notification = [
                'message' => 'Invoice created successfully.',
                'alert-type' => 'success',
            ];

            return redirect()->route('admin.invoice.index')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            $notification = [
                'message' => 'Failed to create invoice: ' . $e->getMessage(),
                'alert-type' => 'error',
            ];
            return back()->with($notification);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('accounting::invoice.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('accounting::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
