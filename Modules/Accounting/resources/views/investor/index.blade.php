@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Investor List') }}</title>
@endsection
@section('admin-content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="section_title">{{ __('Investor List') }}</h4>
                <div>
                    <x-admin.add-button :href="route('admin.investor.create')" />
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive list_table">
                    <table class="table" id="investorTable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Total Investment') }}</th>
                                <th>{{ __('Total Repaid') }}</th>
                                <th width="20%">{{ __('Actions') }}</th>
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
    </script>

    <script>
        $(function() {
            $('#investorTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.investor.index') }}',
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
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'total_invested',
                        name: 'total_invested',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'total_repaid',
                        name: 'total_repaid',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
