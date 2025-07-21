<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Category;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Include 'is_active' in the select statement
            $data = Category::with('parent')->orderBy('id', 'desc')->select(['id', 'name', 'status', 'created_at', 'parent_id']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    // Determine button class and text based on status
                    $buttonClass = $row->status == 1 ? 'btn-success' : 'btn-warning';
                    $buttonText = $row->status == 1 ? 'Active' : 'Inactive';
                    return '<input type="checkbox" onchange="changeStatus(' . $row->id . ')" class="toggle-status-btn" data-id="' . $row->id . '" data-toggle="toggle" ' . ($row->status ? 'checked' : '') . '>';
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.category.edit', $row->id);
                    $deleteUrl = route('admin.category.destroy', $row->id);

                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" data-name="' . $row->name . '" class="edit btn btn-info btn-sm me-1 edit-category-btn"><i class="fas fa-edit"></i> </a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="delete btn btn-danger btn-sm delete-category-btn"><i class="fas fa-trash"></i> </a>';

                    return $btn;
                })
                ->rawColumns(['status', 'actions']) // Add 'status' to rawColumns
                ->make(true);
        }
        $categories = Category::all();



        return view('accounting::category.index', compact('categories'));
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|boolean',
        ]);
        $category = Category::create($data);

        $notification = [
            'message' => __('Category created successfully!'),
            'alert-type' => 'success',
        ];
        return redirect()->route('admin.category.index')->with($notification);
    }

    public function statusUpdate(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->status = !$category->status; // Toggle status
        $category->save();

        return response()->json([
            'success' => true,
            'message' => __('Category status updated successfully!'),
            'status' => $category->status,
        ]);
    }


    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|boolean',
        ]);
        $category = Category::findOrFail($id);
        $category->update($data);

        $notification = [
            'message' => __('Category updated successfully!'),
            'alert-type' => 'success',
        ];
        return redirect()->route('admin.category.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        $notification = [
            'message' => __('Category deleted successfully!'),
            'alert-type' => 'success',
        ];
        return redirect()->route('admin.category.index')->with($notification);
    }
}
