<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Investor;
use Modules\Accounting\Models\Repayment;

class RepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('accounting::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $investorId = request('investor_id');
        if (!$investorId) {
            return redirect()->back()->with([
                'message' => 'Investor ID is required to create a repayment.',
                'alert-type' => 'error'
            ]);
        }

        $investor = Investor::find($investorId);


        return view('accounting::repayment.create', compact('investor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'investment_id' => 'required|exists:investments,id',
            'amount' => 'required|numeric|min:1',
            'repayment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $repayment = Repayment::create([
            'investor_id' => $request->investor_id,
            'investment_id' => $request->investment_id,
            'amount' => $request->amount,
            'repayment_date' => $request->repayment_date,
            'notes' => $request->notes,
        ]);


        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.repayment.edit', ['repayment' => $request->investor_id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.investor.show', ['investor' => $request->investor_id]);
        } else {
            $route = route('admin.repayment.edit', ['investor' => $request->investor_id]);
        }
        return redirect($route)->with($notification);
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
        $repayment = Repayment::findOrFail($id);
        return view('accounting::repayment.edit', compact('repayment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'investment_id' => 'required|exists:investments,id',
            'amount' => 'required|numeric|min:1',
            'repayment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $repayment = Repayment::findOrFail($id);
        $repayment->update([
            'investor_id' => $request->investor_id,
            'investment_id' => $request->investment_id,
            'amount' => $request->amount,
            'repayment_date' => $request->repayment_date,
            'notes' => $request->notes,
        ]);
        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.repayment.edit', ['repayment' => $request->investor_id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.investor.show', ['investor' => $request->investor_id]);
        } else {
            $route = route('admin.repayment.edit', ['investor' => $request->investor_id]);
        }
        return redirect($route)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
