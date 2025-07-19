@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Dashboard') }}</title>
@endsection
@section('admin-content')
    <div class="row g-6">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">MY WALLETS</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="walletsDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $filterText }} {{-- Display current filter text --}}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="walletsDropdown">
                                <li><a class="dropdown-item filter-link" href="#" data-filter="week">This Week</a>
                                </li>
                                <li><a class="dropdown-item filter-link" href="#" data-filter="month">This Month</a>
                                </li>
                                <li><a class="dropdown-item filter-link" href="#" data-filter="year">This Year</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <h2 class="card-text mb-2">BDT {{ number_format($totalBalance, 2) }}</h2>
                    <p class="text-muted">Balance</p>
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <p class="mb-0 text-success">Inflows</p>
                            <h5 class="text-success">BDT {{ number_format($inflows, 2) }}</h5>
                            <small class="text-muted">0% Increase</small> {{-- Placeholder for actual percentage --}}
                        </div>
                        <div>
                            <p class="mb-0 text-danger text-end">Outflows</p>
                            <h5 class="text-danger text-end">BDT {{ number_format($outflows, 2) }}</h5>
                            <small class="text-muted text-end">100% Decrease</small> {{-- Placeholder for actual percentage --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Invoice Card --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">INVOICE</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="invoiceDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $filterText }} {{-- Display current filter text --}}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="invoiceDropdown">
                                <li><a class="dropdown-item filter-link" href="#" data-filter="week">This Week</a>
                                </li>
                                <li><a class="dropdown-item filter-link" href="#" data-filter="month">This Month</a>
                                </li>
                                <li><a class="dropdown-item filter-link" href="#" data-filter="year">This Year</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <h2 class="card-text mb-2">BDT {{ number_format($totalInvoicesAmount, 2) }}</h2>
                    <p class="text-muted">Total {{ $countInvoices }} Invoices</p>
                    {{-- Progress bars would be dynamic based on paid/unpaid --}}
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 75%;" aria-valuenow="75"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <h5 class="mt-3">BDT {{ number_format($totalCollection, 2) }}</h5>
                    <p class="text-muted">Total Collection From {{ $countInvoices }} Invoices</p>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 50%;" aria-valuenow="50"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <a href="{{ route('admin.invoice.create') }}" class="btn btn-primary btn-sm mt-3"><i
                            class="fas fa-plus-circle me-1"></i> Create an Invoice</a>
                </div>
            </div>
        </div>

        {{-- Customer, Vendor, Products Card --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">CUSTOMER, VENDOR, PRODUCTS</h5>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-users fs-4 me-3 text-primary"></i>
                        <div>
                            <h4 class="mb-0">{{ $totalCustomers }} Customers</h4>
                            <a href="{{ route('admin.customer.create') }}" class="text-primary text-decoration-none"><i
                                    class="fas fa-plus-circle me-1"></i> Create a new Customer</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-handshake fs-4 me-3 text-info"></i>
                        <div>
                            {{-- Assuming $totalVendors from backend, if you implement it --}}
                            <h4 class="mb-0">2 Vendors</h4> {{-- Replace 2 with {{ $totalVendors }} if available --}}
                            <a href="#" class="text-info text-decoration-none"><i class="fas fa-plus-circle me-1"></i>
                                Create a new Vendor</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-box-open fs-4 me-3 text-success"></i>
                        <div>
                            <h4 class="mb-0">{{ $totalProducts }} Products</h4>
                            <a href="{{ route('admin.product.create') }}" class="text-success text-decoration-none"><i
                                    class="fas fa-plus-circle me-1"></i> Create a new Product</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Expense Breakdown Card --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">EXPENSE BREAKDOWN</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="expenseDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $filterText }} {{-- Display current filter text --}}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="expenseDropdown">
                                {{-- Changed data-filter to align with 'week', 'month', 'year' --}}
                                <li><a class="dropdown-item filter-link" href="#" data-filter="week">Last 7
                                        Days</a></li>
                                <li><a class="dropdown-item filter-link" href="#" data-filter="month">Last 30
                                        Days</a></li>
                                <li><a class="dropdown-item filter-link" href="#" data-filter="year">This Year</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <h2 class="card-text mb-2">BDT {{ number_format($totalExpensesCurrentPeriod, 2) }}</h2>
                    <p class="text-muted">{{ $filterText }}</p> {{-- Update text based on filter --}}
                    {{-- You can add a small chart here for breakdown --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Get all filter links (for Wallets, Invoice, and Expense cards)
            const filterLinks = $('.filter-link');

            filterLinks.on('click', function(e) {
                e.preventDefault(); // Prevent default link behavior

                const filterValue = $(this).data(
                'filter'); // Get the filter value from data-filter attribute
                const currentUrl = new URL(window.location.href);

                // Set or update the 'filter' query parameter
                currentUrl.searchParams.set('filter', filterValue);

                // Reload the page with the new URL
                window.location.href = currentUrl.toString();
            });

            // Set the active dropdown text based on the current filter
            const currentFilter = new URL(window.location.href).searchParams.get('filter') ||
            'month'; // Default to 'month'
            const filterTextMap = {
                'week': 'This Week',
                'month': 'This Month',
                'year': 'This Year'
            };

            // Custom map for Expense Breakdown to show "Last 7 Days", "Last 30 Days"
            const expenseFilterTextMap = {
                'week': 'Last 7 Days',
                'month': 'Last 30 Days',
                'year': 'This Year'
            };


            const walletsDropdownButton = $('#walletsDropdown');
            const invoiceDropdownButton = $('#invoiceDropdown');
            const expenseDropdownButton = $('#expenseDropdown'); // Get the expense dropdown button

            if (walletsDropdownButton.length) {
                walletsDropdownButton.text(filterTextMap[currentFilter] || 'This Month');
            }
            if (invoiceDropdownButton.length) {
                invoiceDropdownButton.text(filterTextMap[currentFilter] || 'This Month');
            }
            if (expenseDropdownButton.length) {
                // Use the specific map for the expense breakdown dropdown
                expenseDropdownButton.text(expenseFilterTextMap[currentFilter] || 'Last 30 Days');
            }
        });
    </script>
@endpush
