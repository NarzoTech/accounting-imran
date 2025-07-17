@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Transfer List') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="section_title">{{ __('Transfer List') }}</h4>
                    <div>
                        <x-admin.add-button :href="route('admin.transfer.create')" text="Create Transfer" />
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive list_table">
                        <table class="table" id="account-transfers-table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>From Account</th>
                                    <th>To Account</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Reference</th>
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
@endsection

@push('js')
    <script>
        function deleteData(id) {
            let route = "{{ route('admin.transfer.destroy', ':id') }}";
            route = route.replace(':id', id);
            $("#deleteForm").attr("action", route);
            $("#deleteModal").modal('show');
        }

        $('#account-transfers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.transfer.index') }}',
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'from_account_name',
                    name: 'fromAccount.name'
                },
                {
                    data: 'to_account_name',
                    name: 'toAccount.name'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'reference',
                    name: 'reference',
                    defaultContent: 'N/A'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    </script>
@endpush
