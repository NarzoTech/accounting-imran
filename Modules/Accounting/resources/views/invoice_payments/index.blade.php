@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Invoice Payments & Advances List') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="section_title">{{ __('Invoice Payments & Advances List') }}</h4>
                    <div>
                        <x-admin.add-button :href="route('admin.invoice_payments.create')" text="Add New Payment" />
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive list_table">
                        <table class="table" id="invoice-payments-table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Customer</th>
                                    <th>Invoice #</th>
                                    <th>Payment Type</th>
                                    <th>Amount</th>
                                    <th>Account</th>
                                    <th>Method</th>
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
            let route = "{{ route('admin.invoice_payments.destroy', ':id') }}";
            route = route.replace(':id', id);
            $("#deleteForm").attr("action", route);
            $("#deleteModal").modal('show');
        }

        $('#invoice-payments-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.invoice_payments.index') }}',
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'customer_name',
                    name: 'invoice.customer.name'
                },
                {
                    data: 'invoice_number',
                    name: 'invoice.invoice_number'
                },
                {
                    data: 'payment_type',
                    name: 'payment_type'
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
                    data: 'method',
                    name: 'method',
                    defaultContent: 'N/A'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
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
