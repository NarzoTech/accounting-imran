{{-- resources/views/accounting/account/transactions.blade.php --}}
@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Account Transactions') }} - {{ $account->name }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="section_title">{{ __('Transactions for Account') }}: {{ $account->name }} (Balance:
                        {{ number_format($account->balance, 2) }})</h4>
                    <div>
                        <a href="{{ route('admin.account.index') }}"
                            class="btn btn-secondary">{{ __('Back to Accounts') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive list_table">
                        <table class="table" id="accountTransactionsTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Reference</th>
                                    <th>Note</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- DataTables will load content here --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            $('#accountTransactionsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.account.transactions', $account->id) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'reference',
                        name: 'reference'
                    },
                    {
                        data: 'note',
                        name: 'note'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                ]
            });
        });
    </script>
@endpush
