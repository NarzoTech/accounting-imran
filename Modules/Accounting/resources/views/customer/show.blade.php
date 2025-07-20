@extends('admin.layouts.master')

@section('title')
    <title>{{ __('Customer Details') }}: {{ $customer->name }}</title>
@endsection

@section('admin-content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="section_title">{{ __('Customer Details') }}</h4>
                <div>
                    <a href="{{ route('admin.customer.edit', $customer->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-2"></i> {{ __('Edit Customer') }}
                    </a>
                    <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-list me-2"></i> {{ __('Back to List') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Customer Basic Information --}}
                    <div class="col-md-6 mb-4">
                        <div class="card border-primary h-100">
                            <div class="card-header bg-primary">
                                <h5 class="mb-0 text-white">{{ __('Basic Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>{{ __('Name') }}:</strong> {{ $customer->name }}</p>
                                <p><strong>{{ __('Customer Type') }}:</strong> {{ ucfirst($customer->customer_type) }}</p>
                                @if ($customer->customer_type === 'person')
                                    <p><strong>{{ __('First Name') }}:</strong> {{ $customer->first_name }}</p>
                                    <p><strong>{{ __('Last Name') }}:</strong> {{ $customer->last_name }}</p>
                                @endif
                                <p><strong>{{ __('Email') }}:</strong> {{ $customer->email ?? 'N/A' }}</p>
                                <p><strong>{{ __('Phone') }}:</strong> {{ $customer->phone ?? 'N/A' }}</p>
                                <p><strong>{{ __('Fax') }}:</strong> {{ $customer->fax ?? 'N/A' }}</p>
                                <p><strong>{{ __('Website') }}:</strong> <a href="{{ $customer->website }}"
                                        target="_blank">{{ $customer->website ?? 'N/A' }}</a></p>
                                <p><strong>{{ __('Customer Since') }}:</strong>
                                    {{ $customer->created_at->format('d M Y h:i A') }}</p>
                                <p><strong>{{ __('Status') }}:</strong>
                                    <span class="badge {{ $customer->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $customer->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                                <p><strong>{{ __('Notes') }}:</strong> {{ $customer->notes ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    {{-- Customer Address & Financial Overview --}}
                    <div class="col-md-6 mb-4">
                        <div class="card border-info h-100">
                            <div class="card-header bg-info">
                                <h5 class="mb-0 text-white">{{ __('Address & Financial Overview') }}</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>{{ __('Address') }}:</strong> {{ $customer->address ?? 'N/A' }}</p>
                                <p><strong>{{ __('City') }}:</strong> {{ $customer->city ?? 'N/A' }}</p>
                                <p><strong>{{ __('State') }}:</strong> {{ $customer->state ?? 'N/A' }}</p>
                                <p><strong>{{ __('Zip Code') }}:</strong> {{ $customer->zip_code ?? 'N/A' }}</p>
                                <p><strong>{{ __('Country') }}:</strong> {{ $customer->country ?? 'N/A' }}</p>
                                <hr>
                                <h6>{{ __('Financial Summary') }}</h6>
                                <p><strong>{{ __('Opening Balance') }}:</strong> BDT
                                    {{ number_format($customer->opening_balance, 2) }}
                                    @if ($customer->opening_balance_as_of)
                                        (as of {{ $customer->opening_balance_as_of->format('d M Y') }})
                                    @endif
                                </p>
                                <p><strong>{{ __('Total Invoiced Amount') }}:</strong> BDT
                                    {{ number_format($customer->total_invoiced_amount, 2) }}</p>
                                <p><strong>{{ __('Total Paid (Invoice)') }}:</strong> BDT
                                    {{ number_format($customer->total_invoice_paid_amount, 2) }}</p>
                                <p><strong>{{ __('Total Paid (Advance)') }}:</strong> BDT
                                    {{ number_format($customer->total_advance_paid_amount, 2) }}</p>
                                <p>
                                    <strong>{{ __('Current Balance') }}:</strong>
                                    @php
                                        $currentBalance = $customer->current_balance;
                                        $balanceClass = '';
                                        if ($currentBalance > 0) {
                                            $balanceClass = 'text-danger'; // Customer owes money (due)
                                            $balanceText = 'BDT ' . number_format($currentBalance, 2) . ' Due';
                                        } elseif ($currentBalance < 0) {
                                            $balanceClass = 'text-success'; // Customer has credit/advance
                                            $balanceText = 'BDT ' . number_format(abs($currentBalance), 2) . ' Advance';
                                        } else {
                                            $balanceClass = 'text-secondary'; // Balance is zero
                                            $balanceText = 'BDT 0.00';
                                        }
                                    @endphp
                                    <span class="{{ $balanceClass }} font-weight-bold">{{ $balanceText }}</span>
                                </p>
                                <p>
                                    <strong>{{ __('Due Amount') }}:</strong> <span class="text-danger font-weight-bold">BDT
                                        {{ number_format($customer->due_amount, 2) }}</span>
                                </p>
                                <p>
                                    <strong>{{ __('Advance Amount') }}:</strong> <span
                                        class="text-success font-weight-bold">BDT
                                        {{ number_format($customer->advance_amount, 2) }}</span>
                                </p>
                                <a href="{{ route('admin.invoice_payments.create', ['customer_id' => $customer->id]) }}"
                                    class="btn btn-success btn-sm mt-2">
                                    <i class="fas fa-money-bill-wave"></i> {{ __('Receive Payment') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Customer Invoices --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-dark">
                            <div class="card-header bg-dark">
                                <h5 class="mb-0 text-white">{{ __('Recent Invoices') }}</h5>
                            </div>
                            <div class="card-body">
                                @if ($customer->invoices->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Invoice #') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Due Date') }}</th>
                                                    <th>{{ __('Total Amount') }}</th>
                                                    <th>{{ __('Paid Amount') }}</th>
                                                    <th>{{ __('Due Amount') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($customer->invoices->sortByDesc('invoice_date')->take(10) as $invoice)
                                                    {{-- Display recent 10 invoices --}}
                                                    <tr>
                                                        <td>{{ $invoice->invoice_number }}</td>
                                                        <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                                        <td>{{ $invoice->payment_date->format('d M Y') }}
                                                            @if ($invoice->is_overdue)
                                                                <span
                                                                    class="badge bg-danger ml-2">{{ __('Overdue') }}</span>
                                                            @endif
                                                        </td>
                                                        <td>BDT {{ number_format($invoice->total_amount, 2) }}</td>
                                                        <td>BDT {{ number_format($invoice->amount_paid, 2) }}</td>
                                                        <td>BDT {{ number_format($invoice->amount_due, 2) }}</td>
                                                        <td>
                                                            <span
                                                                class="badge {{ $invoice->payment_status === 'Paid' ? 'bg-success' : ($invoice->payment_status === 'Partially Paid' ? 'bg-warning' : 'bg-danger') }}">
                                                                {{ $invoice->payment_status }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.invoice.show', $invoice->id) }}"
                                                                class="btn btn-info btn-sm" title="View Invoice">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            {{-- Add more invoice actions like edit/print if needed --}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('admin.invoice.index', ['customer_id' => $customer->id]) }}"
                                            class="btn btn-dark">
                                            {{ __('View All Invoices') }} <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                @else
                                    <p class="text-center text-muted">{{ __('No invoices found for this customer.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
