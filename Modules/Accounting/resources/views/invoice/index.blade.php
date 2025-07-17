@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Invoice List') }}</title>
@endsection
@section('admin-content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="section_title">{{ __('Invoice List') }}</h4>
                <div>
                    <x-admin.add-button :href="route('admin.invoice.create')" />
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-start mb-3">
                    <div class="btn-group" role="group" aria-label="Invoice Status Filter">
                        <button type="button" class="btn btn-outline-primary active" data-status="all">All</button>
                        <button type="button" class="btn btn-outline-primary" data-status="unpaid">Unpaid</button>
                        <button type="button" class="btn btn-outline-primary" data-status="partial">Partial</button>
                        <button type="button" class="btn btn-outline-primary" data-status="paid">Paid</button>
                        <button type="button" class="btn btn-outline-primary" data-status="overdue">Overdue</button>
                    </div>

                    <!-- Customer Filter -->
                    <div class="ms-3">
                        <select class="form-select" id="customer_filter">
                            <option value="">All Customers</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="table-responsive list_table">
                    <table class="table" id="invoices-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Invoice #</th>
                                <th>Customer</th>
                                <th>Invoice Date</th>
                                <th>Payment Date</th>
                                <th>Total Amount</th>
                                <th>Amount Paid</th>
                                <th>Amount Due</th>
                                <th>Status</th>
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
            var url = "{{ route('admin.invoice.destroy', ':id') }}";
            url = url.replace(':id', id);
            $('#deleteForm').attr('action', url);
            $('#deleteModal').modal('show');
        }


        var invoicesTable = $('#invoices-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.invoice.index') }}',
                data: function(d) {
                    d.status = $('.btn-group .active').data('status');
                    d.customer_id = $('#customer_filter').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'invoice_number',
                    name: 'invoice_number'
                },
                {
                    data: 'customer_name',
                    name: 'customer.name'
                },
                {
                    data: 'invoice_date',
                    name: 'invoice_date'
                },
                {
                    data: 'payment_date',
                    name: 'payment_date'
                },
                {
                    data: 'total_amount',
                    name: 'total_amount'
                },
                {
                    data: 'amount_paid',
                    name: 'amount_paid',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'amount_due',
                    name: 'amount_due',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'payment_status_display',
                    name: 'payment_status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        // Filter button click handler
        $('.btn-group button').on('click', function() {
            $('.btn-group button').removeClass('active');
            $(this).addClass('active');
            invoicesTable.ajax.reload(); // Reload DataTables with new filter
        });

        // Customer filter change handler
        $('#customer_filter').on('change', function() {
            invoicesTable.ajax.reload(); // Reload DataTables with new customer filter
        });
    </script>
@endpush
