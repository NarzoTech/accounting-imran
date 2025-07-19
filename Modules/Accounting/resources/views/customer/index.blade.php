@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Customer List') }}</title>
@endsection
@section('admin-content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="section_title">{{ __('Customer List') }}</h4>
                <div>
                    <x-admin.add-button :href="route('admin.customer.create')" />
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive list_table">
                    <table class="table" id="customer_table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Due</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Invoices in {{ now()->year }}</th>
                                <th>Customer Since</th>
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
    <x-admin.delete-modal />
@endsection

@push('js')
    <script>
        "use strict"

        function deleteData(id) {
            var url = "{{ route('admin.customer.destroy', ':id') }}";
            url = url.replace(':id', id);
            $('#deleteForm').attr('action', url);
            $('#deleteModal').modal('show');
        }
        "use strict"

        function changeStatus(id) {
            let route = "{{ route('admin.customer.status.update', ':id') }}";
            route = route.replace(':id', id);
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: route,
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
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#customer_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.customer.index') }}',
                dom: 'Bfrtip',
                buttons: ['excelHtml5', 'pdfHtml5'],
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'due',
                        name: 'due'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'invoice_count',
                        name: 'invoice_count'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        // Add this new column definition for the 'action' buttons
                        data: 'action', // This matches the 'action' key from your server-side response
                        name: 'action',
                        orderable: false, // Actions column should not be sortable
                        searchable: false // Actions column should not be searchable
                    },
                ]
            });
        });
    </script>
@endpush
