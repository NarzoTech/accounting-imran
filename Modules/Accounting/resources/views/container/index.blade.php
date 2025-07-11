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
                                <th width="5%">{{ __('SN') }}</th>
                                <th width="30%">{{ __('Container') }}</th>
                                <th width="15%">{{ __('Price') }}</th>
                                <th width="10%">{{ __('Category') }}</th>
                                <th width="15%">{{ __('Status') }}</th>
                                <th width="15%">{{ __('Action') }}</th>
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
@endpush
