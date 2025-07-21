<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountTransfer;
use Modules\Accounting\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;

class AccountTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AccountTransfer::with(['fromAccount', 'toAccount'])->select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('from_account_name', function (AccountTransfer $transfer) {
                    return $transfer->fromAccount->name ?? 'N/A';
                })
                ->addColumn('to_account_name', function (AccountTransfer $transfer) {
                    return $transfer->toAccount->name ?? 'N/A';
                })
                ->editColumn('amount', function (AccountTransfer $transfer) {
                    return number_format($transfer->amount, 2);
                })
                ->editColumn('date', function (AccountTransfer $transfer) {
                    return \Carbon\Carbon::parse($transfer->date)->format('Y-m-d');
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.transfer.edit', $row->id);
                    $deleteUrl = route('admin.transfer.destroy', $row->id);

                    return '
                        <a href="' . $editUrl . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-sm delete-transfer" onclick="deleteData(' . $row->id . ')"><i class="fas fa-trash"></i></button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('accounting::transfer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::all();

        return view('accounting::transfer.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_account_id' => 'required|different:to_account_id|exists:accounts,id',
            'to_account_id'   => 'required|exists:accounts,id',
            'amount'          => 'required|numeric|min:0.01',
            'date'            => 'required|date',
            'reference'       => 'nullable|string|max:100',
            'note'            => 'nullable|string',
        ]);

        $transfer = AccountTransfer::create([
            'from_account_id' => $request->from_account_id,
            'to_account_id'   => $request->to_account_id,
            'amount'          => $request->amount,
            'reference'       => $request->reference,
            'note'            => $request->note,
            'date'            => $request->date,
        ]);

        DB::transaction(function () use ($request) {


            // Debit from source account
            $fromAccount = Account::find($request->from_account_id);
            $fromAccount->balance -= $request->amount;
            $fromAccount->save();

            // Credit to destination account
            $toAccount = Account::find($request->to_account_id);
            $toAccount->balance += $request->amount;
            $toAccount->save();

            // Create transactions
            Transaction::create([
                'account_id' => $fromAccount->id,
                'type' => 'transfer_out',
                'amount' => $request->amount,
                'method' => 'Transfer',
                'note' => 'Transfer to ' . $toAccount->name,
                'date' => $request->date,
                'related_type' => 'account_transfer',
            ]);

            Transaction::create([
                'account_id' => $toAccount->id,
                'type' => 'transfer_in',
                'amount' => $request->amount,
                'method' => 'Transfer',
                'note' => 'Transfer from ' . $fromAccount->name,
                'date' => $request->date,
                'related_type' => 'account_transfer',
            ]);
        });



        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];


        if ($request->button == 'save') {
            $route = route('admin.transfer.edit', ['transfer' => $transfer->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.transfer.index');
        } else {
            $route = route('admin.transfer.edit', ['transfer' => $transfer->id]);
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
        $transfer = AccountTransfer::findOrFail($id);
        $accounts = Account::all();
        return view('accounting::transfer.create', compact('transfer', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'from_account_id' => 'required|different:to_account_id|exists:accounts,id',
            'to_account_id'   => 'required|exists:accounts,id',
            'amount'          => 'required|numeric|min:0.01',
            'date'            => 'required|date',
            'reference'       => 'nullable|string|max:100',
            'note'            => 'nullable|string',
        ]);
        $transfer = AccountTransfer::findOrFail($id);

        DB::transaction(function () use ($request, $transfer) {
            // Reverse previous balances
            $oldFrom = Account::find($transfer->from_account_id);
            $oldTo = Account::find($transfer->to_account_id);

            $oldFrom->balance += $transfer->amount;
            $oldFrom->save();

            $oldTo->balance -= $transfer->amount;
            $oldTo->save();

            // Update transfer
            $transfer->update([
                'from_account_id' => $request->from_account_id,
                'to_account_id'   => $request->to_account_id,
                'amount'          => $request->amount,
                'reference'       => $request->reference,
                'note'            => $request->note,
                'date'            => $request->date,
            ]);

            // Apply new balances
            $newFrom = Account::find($request->from_account_id);
            $newTo = Account::find($request->to_account_id);

            $newFrom->balance -= $request->amount;
            $newFrom->save();

            $newTo->balance += $request->amount;
            $newTo->save();

            // Update related transactions
            Transaction::where([
                'account_id' => $oldFrom->id,
                'type' => 'transfer_out',
                'related_type' => 'account_transfer',
            ])->whereDate('date', $transfer->date)->delete();

            Transaction::where([
                'account_id' => $oldTo->id,
                'type' => 'transfer_in',
                'related_type' => 'account_transfer',
            ])->whereDate('date', $transfer->date)->delete();

            Transaction::create([
                'account_id' => $newFrom->id,
                'type' => 'transfer_out',
                'amount' => $request->amount,
                'method' => 'Transfer',
                'note' => 'Transfer to ' . $newTo->name,
                'date' => $request->date,
                'related_type' => 'account_transfer',
            ]);

            Transaction::create([
                'account_id' => $newTo->id,
                'type' => 'transfer_in',
                'amount' => $request->amount,
                'method' => 'Transfer',
                'note' => 'Transfer from ' . $newFrom->name,
                'date' => $request->date,
                'related_type' => 'account_transfer',
            ]);
        });





        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];


        if ($request->button == 'save') {
            $route = route('admin.transfer.edit', ['transfer' => $transfer->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.transfer.index');
        } else {
            $route = route('admin.transfer.edit', ['transfer' => $transfer->id]);
        }
        return redirect($route)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $transfer = AccountTransfer::findOrFail($id);
        DB::transaction(function () use ($transfer) {
            // Reverse balances
            $from = Account::find($transfer->from_account_id);
            $to = Account::find($transfer->to_account_id);

            $from->balance += $transfer->amount;
            $from->save();

            $to->balance -= $transfer->amount;
            $to->save();

            // Delete related transactions
            Transaction::where([
                'account_id' => $from->id,
                'type' => 'transfer_out',
                'related_type' => 'account_transfer',
            ])->whereDate('date', $transfer->date)->delete();

            Transaction::where([
                'account_id' => $to->id,
                'type' => 'transfer_in',
                'related_type' => 'account_transfer',
            ])->whereDate('date', $transfer->date)->delete();

            // Delete the transfer
            $transfer->delete();
        });

        $notification = __('Deleted Successfully');

        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return redirect()->route('admin.transfer.index')->with($notification);
    }
}
