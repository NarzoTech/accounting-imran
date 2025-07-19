<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Accounting\Http\Requests\CustomerRequest;
use Modules\Accounting\Models\Customer;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $year = now()->year;

            $customers = Customer::with(['invoices' => function ($q) use ($year) {
                $q->whereYear('invoice_date', $year);
            }])->get();

            $customers->each(function ($customer) {
                $customer->total_due = $customer->invoices->sum->amount_due;
            });

            return DataTables::of($customers)
                ->addColumn('due', fn($row) => 'BDT ' . number_format($row->total_due ?? 0, 2))
                ->addColumn('invoice_count', fn($row) => $row->invoices->count())
                ->editColumn('created_at', fn($row) => $row->created_at->format('d M Y'))
                ->addColumn('action', function ($row) {
                    // Define URLs for the action buttons
                    $showUrl = route('admin.customer.show', $row->id);
                    $editUrl = route('admin.customer.edit', $row->id);
                    $dueReceiveUrl = route('admin.invoice_payments.create', ['customer_id' => $row->id]); // Link to due receive form

                    // Construct the HTML for the action buttons
                    return '
                        <div class="btn-group" role="group" aria-label="Customer Actions">
                            <a href="' . $showUrl . '" class="btn btn-info btn-sm" title="View Details"><i class="fas fa-eye"></i></a>
                            <a href="' . $editUrl . '" class="btn btn-primary btn-sm" title="Edit Customer"><i class="fas fa-edit"></i></a>
                            <button type="button" onclick="deleteData(' . $row->id . ')" class="btn btn-danger btn-sm" title="Delete Customer"><i class="fas fa-trash"></i></button>
                            <a href="' . $dueReceiveUrl . '" class="btn btn-success btn-sm" title="Receive Due"><i class="fas fa-money-bill-wave"></i> Due</a>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('accounting::customer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounting::customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imagePath = file_upload($image);
            $data['profile_image'] = $imagePath;
        }

        // Set status explicitly if not sent
        $data['status'] = $request->has('status') ? 1 : 0;

        // Set current authenticated user as creator
        $data['user_id'] = Auth::guard('admin')->id();

        $customer = Customer::create($data);


        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.customer.edit', ['customer' => $customer->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.customer.index');
        } else {
            $route = route('admin.customer.edit', ['customer' => $customer->id]);
        }
        return redirect($route)->with($notification);
    }

    /**
     * Show the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->load(['invoices.items', 'invoices.payments']); // Load invoices and their items/payments

        return view('accounting::customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customer = Customer::find($id);
        return view('accounting::customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $data = $request->validated();

        // Handle profile image update
        if ($request->hasFile('profile_image')) {

            $data['profile_image'] = file_upload($request->file('profile_image'), $customer->profile_image);
        }

        $data['status'] = $request->status ? 1 : 0;

        $customer->update($data);


        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.customer.edit', ['customer' => $customer->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.customer.index');
        } else {
            $route = route('admin.customer.edit', ['customer' => $customer->id]);
        }

        return redirect($route)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        try {
            file_delete($customer->profile_image);

            // Delete the customer (soft delete if using SoftDeletes)
            $customer->delete();

            return back()->with([
                'message' => __('Customer deleted successfully.'),
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'message' => __('Failed to delete customer: ') . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }
}
