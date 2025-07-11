<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Http\Requests\ContainerRequest;
use Modules\Accounting\Models\Container;
use Modules\Accounting\Models\Product;

class ContainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('accounting::container.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('status', 1)->get();
        return view('accounting::container.create', compact('products'));
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
            'status',
        ]));

        foreach ($request->products as $item) {
            $container->products()->attach($item['product_id'], [
                'quantity' => $item['quantity'],
            ]);
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
        return view('accounting::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('accounting::container.edit', [
            'container' => Container::with('products')->findOrFail($id),
            'products' => Product::where('status', 1)->get(),
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
            'remarks',
            'status',
        ]));

        // Prepare products data for sync: product_id => [pivot data]
        $productsData = [];
        foreach ($request->products as $item) {
            $productsData[$item['product_id']] = ['quantity' => $item['quantity']];
        }

        // Sync products with quantities
        $container->products()->sync($productsData);

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
