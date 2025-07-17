@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Account List') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="section_title">{{ __('Account List') }}</h4>
                    <div>
                        <x-admin.add-button :href="route('admin.account.create')" text="Add Account" />
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive list_table">
                        <table class="table" id="accountTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Account Name</th>
                                    <th>Type</th>
                                    <th>Account Number</th>
                                    <th>Provider</th>
                                    <th class="text-end">Balance</th>
                                    <th>Note</th>
                                    <th class="text-center">Actions</th>
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
@endsection

@push('js')
    <script>
        function deleteData(id) {
            let route = "{{ route('admin.account.destroy', ':id') }}";
            route = route.replace(':id', id);
            $("#deleteForm").attr("action", route);
            $("#deleteModal").modal('show');
        }
        $('#accountTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.account.index') }}",
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
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'account_number',
                    name: 'account_number'
                },
                {
                    data: 'provider',
                    name: 'provider'
                },
                {
                    data: 'balance',
                    name: 'balance',
                    className: 'text-end'
                },
                {
                    data: 'note',
                    name: 'note'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ]
        });
    </script>
@endpush
