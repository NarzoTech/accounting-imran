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
            }])->withSum('invoices as total_due', 'due_amount');

            return DataTables::of($customers)
                ->addColumn('due', fn($row) => 'BDT ' . number_format($row->total_due ?? 0, 2))
                ->addColumn('invoice_count', fn($row) => $row->invoices->count())
                ->editColumn('created_at', fn($row) => $row->created_at->format('d M Y'))
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
    public function show($id)
    {
        return view('accounting::customer.show');
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
