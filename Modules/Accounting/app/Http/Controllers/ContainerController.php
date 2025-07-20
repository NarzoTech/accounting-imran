<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Http\Requests\ContainerRequest;
use Modules\Accounting\Models\Container;
use Modules\Accounting\Models\Product;
use Yajra\DataTables\Facades\DataTables;

class ContainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Container::select([
                'id',
                'container_number',
                'container_type',
                'shipping_line',
                'port_of_loading',
                'port_of_discharge',
                'status',
            ]);

            return DataTables::of($data)
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.container.edit', $row->id);
                    $showUrl = route('admin.container.show', $row->id);
                    return '
                        <a href="' . $showUrl . '" class="btn btn-info btn-sm me-1"><i class="fas fa-eye"></i></a>
                        <a href="' . $editUrl . '" class="btn btn-primary btn-sm me-1"><i class="fas fa-edit"></i></a>
                        <button onclick="deleteData(' . $row->id . ')" type="button" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('accounting::container.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounting::container.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContainerRequest $request)
    {


        $container = Container::create($request->only([
            'container_number',
            'container_type',
            'shipping_line',
            'port_of_loading',
            'port_of_discharge',
            'estimated_departure',
            'estimated_arrival',
            'actual_arrival',
            'remarks',
            'lc_number',
            'bank_name',
            'status',
        ]));


        if ($request->hasFile('attachment')) {
            $container->attachment = file_upload($request->file('attachment'), 'uploads/files/', prefix: 'attachment');
            $container->save();
        }



        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.container.edit', ['container' => $container->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.container.index');
        } else {
            $route = route('admin.container.edit', ['container' => $container->id]);
        }
        return redirect($route)->with($notification);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $container = Container::findOrFail($id);
        return view('accounting::container.show', compact('container'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('accounting::container.edit', [
            'container' => Container::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContainerRequest $request, Container $container)
    {
        // Update container main data
        $container->update($request->only([
            'container_number',
            'container_type',
            'shipping_line',
            'port_of_loading',
            'port_of_discharge',
            'estimated_departure',
            'estimated_arrival',
            'actual_arrival',
            'lc_number',
            'bank_name',
            'remarks',
            'status',
        ]));

        // Handle file upload for attachment
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($container->attachment) {
                file_delete($container->attachment);
            }
            $container->attachment = file_upload($request->file('attachment'), 'uploads/files/', prefix: 'attachment');
            $container->save(); // Save the updated attachment
        }

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.container.edit', ['container' => $container->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.container.index');
        } else {
            $route = route('admin.container.edit', ['container' => $container->id]);
        }

        return redirect($route)->with($notification);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Container $container)
    {
        // Detach related products (optional, for cleanup)
        $container->products()->detach();

        // Delete the container
        $container->delete();

        // Redirect with success notification
        $notification = ['message' => __('Deleted Successfully'), 'alert-type' => 'success'];
        return redirect()->route('admin.container.index')->with($notification);
    }
}
