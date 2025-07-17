<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Account;
use Yajra\DataTables\Facades\DataTables;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Account::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('type', fn($row) => ucwords(str_replace('_', ' ', $row->type)))
                ->editColumn('balance', fn($row) => number_format($row->balance, 2))
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.account.edit', $row->id);
                    $deleteUrl = route('admin.account.destroy', $row->id); // Assuming you want to use the delete button like in your example
                    return '
                        <a href="' . $editUrl . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-sm delete-account" onclick="deleteData(' . $row->id . ')"><i class="fas fa-trash"></i></button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('accounting::account.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounting::account.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,mobile_banking,card,cash',
            'account_number' => 'nullable|string|max:255',
            'provider' => 'nullable|string|max:255',
            'balance' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $account = Account::create($validatedData);


        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.account.edit', ['account' => $account->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.account.index');
        } else {
            $route = route('admin.account.edit', ['account' => $account->id]);
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
        $account = Account::findOrFail($id);
        return view('accounting::account.create', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,mobile_banking,card,cash',
            'account_number' => 'nullable|string|max:255',
            'provider' => 'nullable|string|max:255',
            'balance' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $account = Account::findOrFail($id);
        $account->update($validatedData);



        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.account.edit', ['account' => $account->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.account.index');
        } else {
            $route = route('admin.account.edit', ['account' => $account->id]);
        }
        return redirect($route)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        $notification = __('Deleted Successfully');
        return redirect()->route('admin.account.index')->with(['message' => $notification, 'alert-type' => 'success']);
    }
}
