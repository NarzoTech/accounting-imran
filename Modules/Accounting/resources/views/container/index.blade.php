@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Container List') }}</title>
@endsection
@section('admin-content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="section_title">{{ __('Container List') }}</h4>
                <div>
                    <x-admin.add-button :href="route('admin.container.create')" />
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive list_table">
                    <table class="table" id="container_table">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Container Number') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Shipping Line') }}</th>
                                <th>{{ __('Port of Loading') }}</th>
                                <th>{{ __('Port of Discharge') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Actions') }}</th>
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
            var url = "{{ route('admin.container.destroy', ':id') }}";
            url = url.replace(':id', id);
            $('#deleteForm').attr('action', url);
            $('#deleteModal').modal('show');
        }
        "use strict"

        function changeStatus(id) {
            let route = "{{ route('admin.container.status.update', ':id') }}";
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
        $(function() {
            $('#container_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.container.index') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'container_number',
                        name: 'container_number'
                    },
                    {
                        data: 'container_type',
                        name: 'container_type'
                    },
                    {
                        data: 'shipping_line',
                        name: 'shipping_line'
                    },
                    {
                        data: 'port_of_loading',
                        name: 'port_of_loading'
                    },
                    {
                        data: 'port_of_discharge',
                        name: 'port_of_discharge'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                    },
                ]
            });

            // Delete Confirmation
            $(document).on('click', '.delete-container', function() {
                if (confirm('Are you sure you want to delete this container?')) {
                    let url = $(this).data('url');
                    $.post(url, {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    }, function(response) {
                        $('#container_table').DataTable().ajax.reload();
                        alert(response.message);
                    });
                }
            });
        });
    </script>
@endpush
