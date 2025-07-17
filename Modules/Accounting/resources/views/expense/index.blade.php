@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Expense List') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="section_title">{{ __('Expense List') }}</h4>
                    <div>
                        <x-admin.add-button :href="route('admin.expense.create')" text="Add Expense" />
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive list_table">
                        <table class="table" id="expenses-table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Title</th>
                                    <th>Amount</th>
                                    <th>Account</th>
                                    <th>Container</th>
                                    <th>Payment Method</th>
                                    <th>Reference</th>
                                    <th>Date</th>
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
            let route = "{{ route('admin.expense.destroy', ':id') }}";
            route = route.replace(':id', id);
            $("#deleteForm").attr("action", route);
            $("#deleteModal").modal('show');
        }

        $('#expenses-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.expense.index') }}',
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'account_name',
                    name: 'account.name'
                },
                {
                    data: 'container_name',
                    name: 'container.name',
                    defaultContent: 'N/A'
                },
                {
                    data: 'payment_method',
                    name: 'payment_method'
                },
                {
                    data: 'reference',
                    name: 'reference'
                },
                {
                    data: 'date',
                    name: 'date'
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
