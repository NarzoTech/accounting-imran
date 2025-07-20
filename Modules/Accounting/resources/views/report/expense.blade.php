@extends('admin.layouts.master')

@section('title')
    <title>{{ __('Expense Report') }}</title>
@endsection


@section('admin-content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Expense Report') }}</h6>
                </div>
                <div class="card-body">
                    <form id="expenseReportForm" action="{{ route('admin.reports.expense') }}" method="GET" class="mb-4">
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

                    <h5 class="mt-4 mb-4">{{ __('Expense Details') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="expenseTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Account') }}</th>
                                    <th>{{ __('Description') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- DataTables will populate this table via AJAX --}}
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
            var expenseTable = $('#expenseTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.reports.get_expense_data') }}",
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columns: [{
                        data: 'date',
                        name: 'created_at'
                    }, // 'created_at' is the database column for sorting
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'account',
                        name: 'account'
                    }, // 'account' is a relation, requires custom sorting if not handled
                    {
                        data: 'description',
                        name: 'note',
                        orderable: false
                    } // 'note' is the database column, 'description' is display, orderable: false if not easily sortable
                ],
                dom: 'lBfrtip', // Added 'l' for length menu
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            // Reload table data when date range changes
            $('#expenseReportForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                expenseTable.ajax.reload(); // Reload DataTables data
            });
        });
    </script>
@endpush
