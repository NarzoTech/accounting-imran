@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Category List') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="section_title">{{ __('Category List') }}</h4>
                    <div>
                        <x-admin.add-button :href="'javascript:;'" data-bs-toggle="modal" data-bs-target="#categoryModal"
                            text="Add Category" />
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive list_table">
                        <table class="table" id="categoryTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Parent Category</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-admin.delete-modal />

    <div tabindex="-1" role="dialog" id="categoryModal" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header mb-0">
                    <h5 class="modal-title">{{ __('Add Product Category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="categoryForm" action="{{ route('admin.category.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="subCategoryParent" class="form-label">Parent Category </label>
                            <select class="select2" id="subCategoryParent" name="parent_id"
                                data-dropdown-parent="#categoryModal">
                                <option value="">None</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="categoryName" required placeholder="Enter Name"
                                name="name">
                        </div>
                        <input type="hidden" name="status" value="1">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger rounded-md shadow-sm"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary rounded-md shadow-sm">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div tabindex="-1" role="dialog" id="editCategoryModal" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header mb-0">
                    <h5 class="modal-title">{{ __('Edit Product Category') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCategoryForm" method="POST" action="{{ route('admin.category.update', ':id') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editCategoryId">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editSubCategoryParent" class="form-label">Parent Category</label>
                            <select class="select2" id="editSubCategoryParent" name="parent_id"
                                data-dropdown-parent="#editCategoryModal">
                                <option value="">None</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editCategoryName" required
                                placeholder="Enter Name" name="name">
                        </div>

                        <div class="mb-3">
                            <label for="editCategoryStatus" class="form-label">Status</label>
                            <select class="form-control" id="editCategoryStatus" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer pt-5">
                        <button type="button" class="btn btn-danger rounded-md shadow-sm"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary rounded-md shadow-sm">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function deleteData(id) {
            let route = "{{ route('admin.category.destroy', ':id') }}";
            route = route.replace(':id', id);
            $("#deleteForm").attr("action", route);
            $("#deleteModal").modal('show');
        }

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/category/status-update') }}" + "/" + id,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    if (err.responseJSON && err.responseJSON.message) {
                        toastr.error(err.responseJSON.message);
                    } else {
                        toastr.error("{{ __('Something went wrong, please try again') }}");
                    }
                }
            })
        }

        $(document).ready(function() {
            // Initialize Category DataTables
            categoryTable = $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.category.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: function(data, type, row) {
                            return data.parent ? data.parent.name :
                                'None';
                        },
                        name: 'parent category',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                responsive: true,
                paging: true,
                searching: true,
                info: true,
                lengthChange: true,
                pageLength: 10
            });
            $('#categoryTable').on('draw.dt', function() {
                $('input[data-toggle="toggle"]').bootstrapToggle({
                    on: 'Active',
                    off: 'Inactive'
                });
            });
            // Handle edit category button click (delegated event)
            $(document).on('click', '.edit-category-btn', function() {
                const id = $(this).data('id');
                // ajax call to get category data
                let $route = "{{ route('admin.category.edit', ':id') }}";
                $route = $route.replace(':id', id);
                $.ajax({
                    url: $route,
                    type: 'GET',
                    success: function({
                        data
                    }) {
                        $('#editCategoryId').val(data.id);
                        $('#editCategoryName').val(data.name);
                        $('#editSubCategoryParent').val(data.parent_id).trigger('change');
                        $('#editCategoryStatus').val(data.status);
                        $('#editCategoryModal').modal('show');


                        // add route to the form
                        $('#editCategoryForm').attr('action',
                            "{{ route('admin.category.update', ':id') }}".replace(':id',
                                data.id));
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });

            // Handle delete category button click (delegated event)
            $('#categoryTable tbody').on('click', '.delete-category-btn', function() {
                const id = $(this).data('id');
                deleteData(id);
            });
        });
    </script>
@endpush
