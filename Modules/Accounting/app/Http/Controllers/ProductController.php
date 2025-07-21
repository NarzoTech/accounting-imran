<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Category;
use Modules\Accounting\Models\Product;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with('category');

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('product', function ($row) {
                    $image = $row->image
                        ? asset($row->image)
                        : asset('uploads/website-images/no-image.jpg');

                    return '
                        <div class="d-flex align-items-center gap-3">
                            <img src="' . $image . '" class="rounded-circle" width="50" height="50" alt="Product Image">
                            <div>
                                <strong>' . $row->name . '</strong><br>
                                SKU: ' . $row->product_code . '
                            </div>
                        </div>
                    ';
                })
                ->addColumn('price', function ($row) {
                    return 'BDT ' . number_format($row->sell_price, 2);
                })
                ->addColumn('category', function ($row) {
                    return $row->category->name ?? 'Uncategorized';
                })
                ->addColumn('status', function ($row) {
                    // Determine button class and text based on status
                    $buttonClass = $row->status == 1 ? 'btn-success' : 'btn-warning';
                    $buttonText = $row->status == 1 ? 'Active' : 'Inactive';
                    return '<input type="checkbox" onchange="changeStatus(' . $row->id . ')" class="toggle-status-btn" data-id="' . $row->id . '" data-toggle="toggle" ' . ($row->status ? 'checked' : '') . '>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.product.edit', $row->id);
                    return '
                        <a href="' . $editUrl . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger btn-sm delete-product" data-id="' . $row->id . '"><i class="fas fa-trash"></i></button>
                    ';
                })
                ->rawColumns(['product', 'status', 'action'])
                ->make(true);
        }
        return view('accounting::products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status', 1)->get();
        $subCategories = Category::where('status', 1)->where('parent_id', '!=', null)->get();
        return view('accounting::products.create', compact('categories', 'subCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_code' => 'required|string|max:255|unique:products,product_code',
            'unit' => 'nullable|string|max:20',
            'cost_price' => 'nullable|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = file_upload($request->file('image'));
            $validated['image'] = $imagePath;
        }
        $validated['status'] = $request->has('status') ? 1 : 0;

        $product = Product::create($validated);


        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.product.edit', ['product' => $product->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.product.index');
        } else {
            $route = route('admin.product.edit', ['product' => $product->id]);
        }
        return redirect($route)->with($notification);
    }

    public function statusUpdate($id)
    {
        $product = Product::findOrFail($id);
        $product->status = !$product->status;
        $product->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('status', 1)->get();
        $subCategories = Category::where('status', 1)->where('parent_id', '!=', null)->get();
        return view('accounting::products.edit', compact('product', 'categories', 'subCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_code' => 'required|string|max:255|unique:products,product_code,' . $product->id,
            'unit' => 'nullable|string|max:20',
            'cost_price' => 'nullable|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = file_upload($request->file('image'),  oldFile: $product->image);
            $validated['image'] = $imagePath;
        }

        $validated['status'] = $request->has('status') ? 1 : 0;

        // Update product
        $product->update($validated);
        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->button == 'save') {
            $route = route('admin.product.edit', ['product' => $product->id]);
        } else if ($request->button == 'save_exit') {
            $route = route('admin.product.index');
        } else {
            $route = route('admin.product.edit', ['product' => $product->id]);
        }
        return redirect($route)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        file_delete($product->image);

        // Delete the product
        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
    }
}
