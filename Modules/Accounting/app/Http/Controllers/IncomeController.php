<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountTransaction;
use Modules\Accounting\Models\Container;
use Modules\Accounting\Models\Income;
use Modules\Accounting\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Income::with(['account', 'container'])->select('*'); // Eager load relationships

            return DataTables::of($data)
                ->addIndexColumn() // Adds a sequential number column
                ->addColumn('account_name', function (Income $income) {
                    return $income->account->name ?? 'N/A';
                })
                ->addColumn('container_name', function (Income $income) {
                    return $income->container->container_number . ' (' . $income->container->container_type . ')' ?? 'N/A';
                })
                ->editColumn('amount', function (Income $income) {
                    return number_format($income->amount, 2); // Format amount nicely
                })
                ->editColumn('date', function (Income $income) {
                    return \Carbon\Carbon::parse($income->date)->format('Y-m-d'); // Format date
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.income.edit', $row->id);


                    return '
                        <a href="' . $editUrl . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-sm delete-income" onclick="deleteData(' . $row->id . ')"><i class="fas fa-trash"></i></button>
                    ';
                })
                ->rawColumns(['actions']) // Important: specify columns that contain raw HTML
                ->make(true);
        }
        return view('accounting::income.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $containers = Container::all();
        $accounts = Account::all();
        return view('accounting::income.create', compact('containers', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'account_id' => 'required|exists:accounts,id',
            'container_id' => 'nullable|exists:containers,id',
            'payment_method' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:2048',
            'note' => 'nullable|string',
            'date' => 'required|date',
        ]);

        if ($request->hasFile('attachment')) {
            $validatedData['attachment'] = file_upload($request->file('attachment'), 'uploads/files/', prefix: 'attachment');
        }
        $income = Income::create($validatedData);
        DB::transaction(
            function () use ($request, $income) {

                AccountTransaction::create([
                    'account_id'   => $income->account_id,
                    'type'         => 'deposit', // Use 'deposit' as the type for income
                    'amount'       => $income->amount,
                    'reference'    => $income->reference ?? 'Income: ' . $income->title,
                    'note'         => $income->note ?? $income->title,
                ]);
            }
        );

        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];


        if ($request->button == 'save') {
            $route = route('admin.income.edit', ['income' => $income->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.income.index');
        } else {
            $route = route('admin.income.edit', ['income' => $income->id]);
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
        $income = Income::findOrFail($id);
        $containers = Container::all();
        $accounts = Account::all();
        return view('accounting::income.create', compact('income', 'containers', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'account_id' => 'required|exists:accounts,id',
            'container_id' => 'nullable|exists:containers,id',
            'payment_method' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:2048',
            'note' => 'nullable|string',
            'date' => 'required|date',
        ]);


        $income = Income::findOrFail($id);
        $oldAccountId = $income->account_id;
        $oldAmount = $income->amount;

        if ($request->hasFile('attachment')) {
            if ($income->attachment) {
                file_delete($income->attachment);
            }
            $validatedData['attachment'] = file_upload($request->file('attachment'), 'uploads/files/', prefix: 'attachment');
        }

        if ($request->hasFile('attachment')) {
            if ($income->attachment) {

                file_delete($income->attachment);
            }
            $validatedData['attachment'] = file_upload($request->file('attachment'), 'uploads/files/', prefix: 'attachment');
        } else {

            unset($validatedData['attachment']);
        }


        DB::transaction(
            function () use ($income, $validatedData, $oldAccountId) {


                $income->update($validatedData);


                AccountTransaction::where('account_id', $oldAccountId)
                    ->where('type', 'deposit') // Assuming income is always a 'deposit' type
                    ->where('reference', 'LIKE', 'Income #' . $income->id . ':%') // Match previous reference pattern
                    ->delete();

                AccountTransaction::create([
                    'account_id'   => $income->account_id, // Use the potentially new (updated) account ID
                    'type'         => 'deposit',           // Still a 'deposit' type transaction
                    'amount'       => $income->amount,     // Use the new (updated) amount
                    'reference'    => 'Income #' . $income->id . ': ' . ($income->reference ?? $income->title), // Generate consistent reference
                    'note'         => $income->note ?? $income->title,

                ]);
            }
        );

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];


        if ($request->button == 'save') {
            $route = route('admin.income.edit', ['income' => $income->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.income.index');
        } else {
            $route = route('admin.income.edit', ['income' => $income->id]);
        }
        return redirect($route)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $income = Income::findOrFail($id);


            // Delete related transaction
            AccountTransaction::where('account_id', $income->account_id)
                ->where('type', 'deposit') // Income transactions are recorded as 'deposit'
                ->where('reference', 'LIKE', 'Income #' . $income->id . ':%')
                ->delete();

            // Delete attachment
            if ($income->attachment) {
                file_delete($income->attachment);
            }


            // Delete income
            $income->delete();
        });

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.income.index')->with($notification);
    }
}
