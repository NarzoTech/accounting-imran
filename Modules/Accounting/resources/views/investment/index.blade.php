@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Investment List') }}</title>
@endsection
@section('admin-content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="section_title">{{ __('Investment List') }}</h4>
                <div>
                    <x-admin.add-button :href="route('admin.investment.create')" />
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive list_table">
                    <table class="table" id="investment_table">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>{{ __('Investor') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Expected Profit') }}</th>
                                <th>{{ __('Total Repaid') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th width="15%">{{ __('Actions') }}</th>
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
            var url = "{{ route('admin.investment.destroy', ':id') }}";
            url = url.replace(':id', id);
            $('#deleteForm').attr('action', url);
            $('#deleteModal').modal('show');
        }
        "use strict"
    </script>

    <script>
        $(function() {
            $('#investment_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.investment.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'investor',
                        name: 'investor.name'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'expected_profit',
                        name: 'expected_profit'
                    },
                    {
                        data: 'total_repaid',
                        name: 'total_repaid'
                    },
                    {
                        data: 'investment_date',
                        name: 'investment_date'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
