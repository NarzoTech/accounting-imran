<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Container;
use Modules\Accounting\Models\Investment;
use Modules\Accounting\Models\Investor;
use Yajra\DataTables\Facades\DataTables;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Investment::with(['investor', 'container'])->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('amount', fn($row) => number_format($row->amount, 2))
                ->editColumn('expected_profit', fn($row) => number_format($row->expected_profit, 2))
                ->editColumn('total_repaid', fn($row) => number_format($row->total_repaid, 2))
                ->editColumn('investment_date', fn($row) => $row->investment_date->format('Y-m-d'))
                ->addColumn('investor', fn($row) => $row->investor->name ?? '-')
                ->addColumn('container', fn($row) => $row->container->container_number ?? '-')
                ->addColumn('action', function ($row) {
                    $edit = route('admin.investment.edit', $row->id);
                    $show = route('admin.investment.show', $row->id);

                    return '
                    <a href="' . $show . '" class="btn btn-sm btn-info me-1"><i class="fas fa-eye"></i></a>
                    <a href="' . $edit . '" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                    <button type="button" onclick="deleteData(' . $row->id . ')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('accounting::investment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $investors = Investor::all();
        $containers = Container::all();
        return view('accounting::investment.create', compact('investors', 'containers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'investor_id'      => 'required|exists:investors,id',
            'container_id'     => 'nullable|exists:containers,id',
            'amount'           => 'required|numeric|min:0.01',
            'investment_date'  => 'required|date',
            'expected_profit'  => 'nullable|numeric|min:0',
            'remarks'          => 'nullable|string',
        ]);

        $investment = Investment::create([
            'investor_id'      => $validated['investor_id'],
            'container_id'     => $validated['container_id'] ?? null,
            'amount'           => $validated['amount'],
            'investment_date'  => $validated['investment_date'],
            'expected_profit'  => $validated['expected_profit'] ?? 0,
            'remarks'          => $validated['remarks'] ?? null,
            'total_repaid'     => 0, // default
        ]);

        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];



        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.investment.edit', ['investment' => $investment->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.investment.index');
        } else {
            $route = route('admin.investment.edit', ['investment' => $investment->id]);
        }
        return redirect($route)->with($notification);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $investment = Investment::with(['investor', 'container', 'repayments'])->findOrFail($id);
        return view('accounting::investment.show', compact('investment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $investment = Investment::findOrFail($id);

        $investors = Investor::all();
        $containers = Container::all();
        return view('accounting::investment.edit', compact('investment', 'investors', 'containers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $investment = Investment::findOrFail($id);

        $validated = $request->validate([
            'investor_id'      => 'required|exists:investors,id',
            'container_id'     => 'nullable|exists:containers,id',
            'amount'           => 'required|numeric|min:0.01',
            'investment_date'  => 'required|date',
            'expected_profit'  => 'nullable|numeric|min:0',
            'remarks'          => 'nullable|string',
        ]);

        $investment->update([
            'investor_id'      => $validated['investor_id'],
            'container_id'     => $validated['container_id'] ?? null,
            'amount'           => $validated['amount'],
            'investment_date'  => $validated['investment_date'],
            'expected_profit'  => $validated['expected_profit'] ?? 0,
            'remarks'          => $validated['remarks'] ?? null,
        ]);

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.investment.edit', ['investment' => $investment->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.investment.index');
        } else {
            $route = route('admin.investment.edit', ['investment' => $investment->id]);
        }
        return redirect($route)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $investment = Investment::findOrFail($id);
        $investment->delete();

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => $notification['message']]);
        }

        if (request()->button == 'save') {
            $route = route('admin.investment.index');
        } else if (request()->button == 'save_exit') {
            $route = route('admin.investment.index');
        } else {
            $route = route('admin.investment.index');
        }
        return redirect($route)->with($notification);
    }
}
