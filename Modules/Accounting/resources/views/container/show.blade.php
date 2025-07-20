@extends('admin.layouts.master')

@section('title')
    <title>{{ __('Container Details') }}: {{ $container->container_number ?? 'N/A' }}</title>
@endsection

@section('admin-content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-12">
                <a href="{{ route('admin.container.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Containers') }}
                </a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title">{{ __('Container Information') }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ __('Container Number') }}:</strong> {{ $container->container_number ?? 'N/A' }}</p>
                        <p><strong>{{ __('Container Type') }}:</strong> {{ $container->container_type ?? 'N/A' }}</p>
                        <p><strong>{{ __('Shipping Line') }}:</strong> {{ $container->shipping_line ?? 'N/A' }}</p>
                        <p><strong>{{ __('Port of Loading') }}:</strong> {{ $container->port_of_loading ?? 'N/A' }}</p>
                        <p><strong>{{ __('Port of Discharge') }}:</strong> {{ $container->port_of_discharge ?? 'N/A' }}
                        </p>
                        <p><strong>{{ __('Current Status') }}:</strong> <span
                                class="badge bg-{{ match ($container->status) {
                                    'Pending' => 'warning',
                                    'In Transit' => 'info',
                                    'Arrived' => 'primary',
                                    'Cleared' => 'success',
                                    'Delivered' => 'success', // or another distinct color like 'dark' for final
                                    default => 'secondary',
                                } }}">{{ $container->status ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ __('Estimated Departure') }}:</strong>
                            {{ $container->estimated_departure ? \Carbon\Carbon::parse($container->estimated_departure)->format('M d, Y') : 'N/A' }}
                        </p>
                        <p><strong>{{ __('Estimated Arrival') }}:</strong>
                            {{ $container->estimated_arrival ? \Carbon\Carbon::parse($container->estimated_arrival)->format('M d, Y') : 'N/A' }}
                        </p>
                        <p><strong>{{ __('Actual Arrival') }}:</strong>
                            {{ $container->actual_arrival ? \Carbon\Carbon::parse($container->actual_arrival)->format('M d, Y') : 'N/A' }}
                        </p>
                        <p><strong>{{ __('LC Number') }}:</strong> {{ $container->lc_number ?? 'N/A' }}</p>
                        <p><strong>{{ __('Bank Name') }}:</strong> {{ $container->bank_name ?? 'N/A' }}</p>
                        <p><strong>{{ __('Attachment') }}:</strong>
                            @if ($container->attachment)
                                <a href="{{ asset('storage/' . $container->attachment) }}"
                                    target="_blank">{{ __('View Attachment') }}</a>
                            @else
                                {{ __('No Attachment') }}
                            @endif
                        </p>
                        <p><strong>{{ __('Remarks') }}:</strong> {{ $container->remarks ?? 'N/A' }}</p>
                    </div>
                </div>
                <hr>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p><strong>{{ __('Total Invoices') }}:</strong> {{ $container->invoices->count() }}</p>
                        <p><strong>{{ __('Total Invoiced Amount') }}:</strong>
                            {{ number_format($container->total_invoiced_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title">{{ __('Invoices in this Container') }}</h4>
            </div>
            <div class="card-body">
                @if ($container->invoices->isEmpty())
                    <p>{{ __('No invoices found for this container.') }}</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Invoice Number') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Invoice Date') }}</th>
                                    <th>{{ __('Total Amount') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($container->invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->customer->name ?? 'N/A' }}</td> {{-- Assuming Invoice has a 'customer' relationship --}}
                                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</td>
                                        <td>{{ number_format($invoice->total_amount, 2) }}</td>
                                        <td>
                                            <a href="{{ route('admin.invoice.show', $invoice->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            {{-- Add other actions like edit/delete if needed --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
