<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountTransaction;
use Modules\Accounting\Models\Container;
use Modules\Accounting\Models\Expense;
use Modules\Accounting\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Expense::with(['account', 'container'])->select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('account_name', function (Expense $expense) {
                    return $expense->account->name ?? 'N/A';
                })
                ->addColumn('container_name', function (Expense $expense) {
                    return $expense->container->name ?? 'N/A';
                })
                ->editColumn('amount', function (Expense $expense) {
                    return number_format($expense->amount, 2);
                })
                ->editColumn('date', function (Expense $expense) {
                    return \Carbon\Carbon::parse($expense->date)->format('Y-m-d');
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.expense.edit', $row->id);
                    return '
                        <a href="' . $editUrl . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-sm delete-expense" onclick="deleteData(' . $row->id . ')"><i class="fas fa-trash"></i></button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('accounting::expense.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::all();
        $containers = Container::all();
        return view('accounting::expense.create', compact('accounts', 'containers'));
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
        $expense = Expense::create($validatedData);

        DB::transaction(
            function () use ($request, $expense) {

                // Create transaction
                AccountTransaction::create([
                    'account_id'   => $expense->account_id,
                    'type'         => 'expense', // Type for expenses
                    'amount'       => $expense->amount,
                    'reference'    => 'Expense #' . $expense->id . ': ' . ($expense->reference ?? $expense->title), // Consistent reference
                    'note'         => $expense->note ?? $expense->title,
                ]);
            }
        );


        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];


        if ($request->button == 'save') {
            $route = route('admin.expense.edit', ['expense' => $expense->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.expense.index');
        } else {
            $route = route('admin.expense.edit', ['expense' => $expense->id]);
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
        $expense = Expense::find($id);
        $accounts = Account::all();
        $containers = Container::all();
        return view('accounting::expense.create', compact('expense', 'accounts', 'containers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);


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

            if ($expense->attachment) {
                file_delete($expense->attachment);
            }

            $validatedData['attachment'] = file_upload($request->file('attachment'), 'uploads/files/', prefix: 'attachment');
        }

        $oldAccountId = $expense->account_id;
        if ($request->hasFile('attachment')) {
            if ($expense->attachment) {

                file_delete($expense->attachment);
            }
            $validatedData['attachment'] = file_upload($request->file('attachment'), 'uploads/files/', prefix: 'attachment');
        } else {

            unset($validatedData['attachment']);
        }
        DB::transaction(function () use ($id, $validatedData, $oldAccountId) {
            $expense = Expense::findOrFail($id);

            // Update expense
            $expense->update($validatedData);


            // Update transaction
            AccountTransaction::where('account_id', $oldAccountId)
                ->where('type', 'expense')
                ->where('reference', 'LIKE', 'Expense #' . $expense->id . ':%')
                ->delete();

            AccountTransaction::create([
                'account_id'   => $expense->account_id, // Use the new (potentially updated) account ID
                'type'         => 'expense',            // Still an expense
                'amount'       => $expense->amount,     // Use the new (updated) amount
                'reference'    => 'Expense #' . $expense->id . ': ' . ($expense->reference ?? $expense->title),
                'note'         => $expense->note ?? $expense->title,
            ]);
        });



        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];


        if ($request->button == 'save') {
            $route = route('admin.expense.edit', ['expense' => $expense->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.expense.index');
        } else {
            $route = route('admin.expense.edit', ['expense' => $expense->id]);
        }
        return redirect($route)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $expense = Expense::findOrFail($id);

            // Delete related transaction
            AccountTransaction::where('account_id', $expense->account_id)
                ->where('type', 'expense') // Expense transactions are recorded as 'expense'
                ->where('reference', 'LIKE', 'Expense #' . $expense->id . ':%')
                ->delete();

            // Delete the attachment if exists

            if ($expense->attachment) {
                file_delete($expense->attachment);
            }

            // Delete the expense
            $expense->delete();
        });

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return redirect()->route('admin.expense.index')->with($notification);
    }
}
