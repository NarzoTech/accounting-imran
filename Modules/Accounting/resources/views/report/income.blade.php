@extends('admin.layouts.master')

@section('title')
    <title>{{ __('Income Report') }}</title>
@endsection

@section('admin-content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Income Report') }}</h6>
                </div>
                <div class="card-body">
                    <form id="incomeReportForm" action="{{ route('admin.reports.income') }}" method="GET" class="mb-4">
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-4">
                                <div class="form-group">
                                    <label for="start_date">{{ __('Start Date') }}</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ $startDate }}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="form-group">
                                    <label for="end_date">{{ __('End Date') }}</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ $endDate }}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">{{ __('Generate Report') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <h5 class="mt-4 mb-4">{{ __('Income Details') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="incomeTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Account') }}</th>
                                    <th>{{ __('Description') }}</th>
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
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            var incomeTable = $('#incomeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.reports.get_income_data') }}",
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columns: [{
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'account',
                        name: 'account'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        orderable: false
                    }
                ],
                dom: 'lBfrtip', // Added 'l' for length menu
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            // Reload table data when date range changes
            $('#incomeReportForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                incomeTable.ajax.reload(); // Reload DataTables data
            });
        });
    </script>
@endpush
