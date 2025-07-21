@extends('admin.layouts.master')

@section('title')
    <title>{{ __('Container Financial Report') }}</title>
@endsection

@section('admin-content')
    <div class="card">
        <div class="card-header">
            <h4 class="section_title">{{ __('Container Financial Report') }}</h4>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <label for="container_dropdown" class="form-label">{{ __('Select Container') }}:</label>
                <select id="container_dropdown" class="form-select">
                    <option value="">{{ __('Please Select a Container') }}</option>
                    @foreach ($containers as $container)
                        <option value="{{ $container->id }}">{{ $container->container_number }}</option>
                    @endforeach
                </select>
            </div>

            <hr>

            <div id="summary_section" class="mb-4 p-3 border rounded" style="display: none;">
                <h5>{{ __('Financial Summary for Container') }} <span id="selected_container_name"
                        class="text-primary"></span></h5>
                <p><strong>{{ __('Total Income') }}:</strong> <span id="total_income_display" class="text-success"></span>
                </p>
                <p><strong>{{ __('Total Expenses') }}:</strong> <span id="total_expense_display" class="text-danger"></span>
                </p>
                <p><strong>{{ __('Net Balance') }}:</strong> <span id="balance_display"></span></p>
            </div>

            <hr>

            <h5 class="mt-4">{{ __('General Income Transactions') }}</h5>
            <div class="table-responsive mb-4">
                <table id="income_table" class="table table-bordered table-striped display dataTable">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Account') }}</th>
                            <th>{{ __('Reference') }}</th>
                            <th>{{ __('Note') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables will populate this tbody --}}
                    </tbody>
                </table>
            </div>

            <h5 class="mt-4">{{ __('Invoice Payments') }}</h5>
            <div class="table-responsive mb-4">
                <table id="invoice_payments_table" class="table table-bordered table-striped display dataTable">
                    <thead>
                        <tr>
                            <th>{{ __('Payment Date') }}</th>
                            <th>{{ __('Invoice No.') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Account') }}</th>
                            <th>{{ __('Reference') }}</th>
                            <th>{{ __('Note') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables will populate this tbody --}}
                    </tbody>
                </table>
            </div>

            <h5 class="mt-4">{{ __('Expense Transactions') }}</h5>
            <div class="table-responsive">
                <table id="expense_table" class="table table-bordered table-striped display dataTable">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Account') }}</th>
                            <th>{{ __('Reference') }}</th>
                            <th>{{ __('Note') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables will populate this tbody --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            var incomeTable = $('#income_table').DataTable({
                processing: true, // Keep this as true for automatic indicator
                serverSide: false, // Data is loaded manually via AJAX
                searching: true,
                paging: true,
                info: true,
                columns: [{
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        render: function(data, type, row) {
                            return `BDT ${parseFloat(data).toFixed(2)}`;
                        }
                    },
                    {
                        data: 'account.name',
                        name: 'account.name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'reference',
                        name: 'reference',
                        defaultContent: ''
                    },
                    {
                        data: 'note',
                        name: 'note',
                        defaultContent: ''
                    }
                ]
            });

            var invoicePaymentsTable = $('#invoice_payments_table').DataTable({
                processing: true,
                serverSide: false,
                searching: true,
                paging: true,
                info: true,
                columns: [

                    {
                        data: function(row) {
                            if (!row.created_at) return 'N/A';

                            const date = new Date(row.created_at);
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(2,
                                '0'); // Months are 0-based
                            const day = String(date.getDate()).padStart(2, '0');

                            return `${year}-${month}-${day}`;
                        },
                        name: 'created_at'
                    },
                    {
                        data: 'invoice.invoice_number',
                        name: 'invoice.invoice_number',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        render: function(data, type, row) {
                            return `BDT ${parseFloat(data).toFixed(2)}`;
                        }
                    },
                    {
                        data: 'account.name',
                        name: 'account.name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'reference',
                        name: 'reference',
                        defaultContent: ''
                    },
                    {
                        data: 'note',
                        name: 'note',
                        defaultContent: ''
                    }
                ]
            });
            var expenseTable = $('#expense_table').DataTable({
                processing: true, // Keep this as true for automatic indicator
                serverSide: false, // Data is loaded manually via AJAX
                searching: true,
                paging: true,
                info: true,
                columns: [{
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        render: function(data, type, row) {
                            return `BDT ${parseFloat(data).toFixed(2)}`;
                        }
                    },
                    {
                        data: 'account.name',
                        name: 'account.name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'reference',
                        name: 'reference',
                        defaultContent: ''
                    },
                    {
                        data: 'note',
                        name: 'note',
                        defaultContent: ''
                    }
                ]
            });

            // Function to load data for a selected container
            function loadContainerData(containerId) {
                if (!containerId) {
                    // Clear summary and tables if no container is selected
                    $('#summary_section').hide();
                    incomeTable.clear().draw();
                    invoicePaymentsTable.clear().draw();
                    expenseTable.clear().draw();
                    return;
                }

                $.ajax({
                    url: `{{ route('admin.reports.container', ['container_id' => '__container_id__']) }}`
                        .replace('__container_id__', containerId),
                    method: 'GET',
                    success: function(response) {
                        // Update summary section
                        $('#selected_container_name').text(response.container_number);
                        $('#total_income_display').text(
                            `BDT ${response.summary.total_income.toFixed(2)}`);
                        $('#total_expense_display').text(
                            `BDT ${response.summary.total_expense.toFixed(2)}`);

                        let balanceClass = response.summary.balance >= 0 ? 'text-primary' :
                            'text-danger';
                        $('#balance_display').html(
                            `<span class="${balanceClass}">BDT ${response.summary.balance.toFixed(2)}</span>`
                        );
                        $('#summary_section').show(); // Show summary section once data is loaded

                        // Update DataTables
                        incomeTable.clear().rows.add(response.general_incomes)
                            .draw(); // Use general_incomes
                        invoicePaymentsTable.clear().rows.add(response.invoice_payments)
                            .draw(); // New DataTable
                        expenseTable.clear().rows.add(response.expenses).draw();
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error, xhr.responseText);
                        alert('{{ __('Error loading container data.') }}');
                    }
                });
            }

            // Handle dropdown change event
            $('#container_dropdown').on('change', function() {
                var selectedContainerId = $(this).val();
                loadContainerData(selectedContainerId);
            });
        });
    </script>
@endpush
