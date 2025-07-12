<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Accounting\Models\Container;
use Modules\Accounting\Models\Customer;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\Product;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('accounting::invoice.index');
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
        return view('accounting::show');
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
