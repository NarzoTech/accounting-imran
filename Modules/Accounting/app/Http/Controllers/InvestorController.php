<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Investor;
use Yajra\DataTables\Facades\DataTables;

class InvestorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Investor::withSum('investments', 'amount')
                ->withSum('repayments', 'amount')
                ->select('investors.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('total_invested', fn($row) => number_format($row->investments_sum_amount ?? 0, 2))
                ->editColumn('total_repaid', fn($row) => number_format($row->repayments_sum_amount ?? 0, 2))
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.investor.edit', $row->id);
                    $showUrl = route('admin.investor.show', $row->id);
                    $repayUrl = route('admin.repayment.create', ['investor_id' => $row->id]);

                    return view('accounting::investor.partials.actions', compact('editUrl', 'showUrl', 'repayUrl', 'row'))->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('accounting::investor.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounting::investor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'notes'   => 'nullable|string',
        ]);


        $investor = Investor::create($validated);


        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.investor.edit', ['investor' => $investor->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.investor.index');
        } else {
            $route = route('admin.investor.edit', ['investor' => $investor->id]);
        }
        return redirect($route)->with($notification);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $investor = Investor::with(['investments', 'repayments'])->findOrFail($id);
        $totalInvested = $investor->investments()->sum('amount');
        $totalRepaid = $investor->repayments()->sum('amount');

        return view('accounting::investor.show', compact('investor', 'totalInvested', 'totalRepaid'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $investor = Investor::findOrFail($id);
        return view('accounting::investor.edit', compact('investor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'notes'   => 'nullable|string',
        ]);

        $investor = Investor::findOrFail($id);
        $investor->update($validated);

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.investor.edit', ['investor' => $investor->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.investor.index');
        } else {
            $route = route('admin.investor.edit', ['investor' => $investor->id]);
        }
        return redirect($route)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $investor = Investor::findOrFail($id);
        $investor->delete();

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.investor.index')->with($notification);
    }
}
