@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Invoice Details') }} - {{ $invoice->invoice_number }}</title>
@endsection
@section('admin-content')
    <div class="content-area">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">{{ __('Invoice Details') }}</h4>
                    <div>
                        <button class="btn btn-primary btn-sm me-2" onclick="window.print()">
                            <i class="fas fa-print"></i> {{ __('Print Invoice') }}
                        </button>
                        <a href="{{ route('admin.invoice.download', $invoice->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-download"></i> {{ __('Download PDF') }}
                        </a>
                        <a href="{{ route('admin.invoice.index') }}" class="btn btn-secondary btn-sm ms-2">
                            <i class="fas fa-list"></i> {{ __('Back to List') }}
                        </a>
                    </div>
                </div>

                <div class="invoice-header-info">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="info-row">
                                <span class="info-label">{{ __('Invoice Number') }} :</span>
                                <span class="info-value">#{{ $invoice->invoice_number }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">{{ __('PO/SO Number') }} :</span>
                                <span class="info-value">{{ $invoice->po_so_number ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">{{ __('Created At') }} :</span>
                                <span class="info-value">{{ $invoice->created_at->format('d M Y H:i A') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <div class="d-flex flex-column align-items-end">
                                <div class="date-box mb-2">
                                    {{ __('Invoice Date') }}: {{ $invoice->invoice_date->format('d M Y') }}
                                </div>
                                <div class="date-box {{ $invoice->is_overdue ? 'due-overdue' : 'due' }}">
                                    {{ __('Due Date') }}: {{ $invoice->payment_date->format('d M Y') }}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4 mt-4">
                    <div class="col-md-6">
                        <div class="invoice-customer-details">
                            <h5>{{ __('Invoice To') }}</h5>
                            <p><strong>{{ $invoice->customer->name ?? 'N/A' }}</strong></p>
                            <p>{{ $invoice->customer->address ?? 'N/A' }}</p>
                            <p>{{ $invoice->customer->city ?? '' }}{{ $invoice->customer->state ? ', ' . $invoice->customer->state : '' }}{{ $invoice->customer->zip_code ? ' - ' . $invoice->customer->zip_code : '' }}
                            </p>
                            <p>{{ $invoice->customer->country ?? '' }}</p>
                            <p>{{ __('Email') }}: {{ $invoice->customer->email ?? 'N/A' }}</p>
                            <p>{{ __('Phone') }}: {{ $invoice->customer->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="invoice-company-details">
                            <h5>{{ $setting->app_name }}</h5>
                            <p>{{ $setting->company_address }}</p>
                            <p>{{ $setting->company_email }}</p>
                            <p>{{ $setting->company_phone }}</p>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3">{{ __('Invoice Items') }}</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered item-details-table">
                        <thead>
                            <tr>
                                <th>{{ __('Item') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Qty') }}</th>
                                <th>{{ __('Unit') }}</th> {{-- Added Unit column --}}
                                <th>{{ __('Unit Price') }}</th>
                                {{-- <th>{{ __('Vat') }}</th> --}} {{-- Removed VAT if not in migration --}}
                                <th>{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoice->items as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'N/A' }}</td> {{-- Assuming product relationship --}}
                                    <td>{{ $item->description ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit ?? 'N/A' }}</td> {{-- Display unit --}}
                                    <td>{{ currency($item->price) }}</td>
                                    {{-- <td>{{ currency(0) }}</td> --}} {{-- VAT not in migration --}}
                                    <td>{{ currency($item->amount) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('No items found for this invoice.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end mb-4">
                    <div class="col-md-6 col-lg-4">
                        <table class="summary-table">
                            <tbody>
                                <tr>
                                    <td class="label">{{ __('Subtotal') }} :</td>
                                    <td class="value">{{ currency($invoice->subtotal) }}</td>
                                </tr>
                                <tr>
                                    <td class="label">{{ __('Discount') }} ({{ $invoice->discount_percentage }}%) :</td>
                                    <td class="value">
                                        {{ currency($invoice->total_amount - $invoice->subtotal - $invoice->delivery_charge) }}
                                    </td> {{-- Calculate actual discount amount --}}
                                </tr>
                                <tr>
                                    <td class="label">{{ __('Delivery Charge') }} :</td>
                                    <td class="value">{{ currency($invoice->delivery_charge) }}</td>
                                </tr>
                                <tr class="total-row">
                                    <td class="label">{{ __('Total') }} :</td>
                                    <td class="value">{{ currency($invoice->total_amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="label">{{ __('Amount Paid') }} :</td>
                                    <td class="value">{{ currency($invoice->amount_paid) }}</td>
                                </tr>
                                <tr>
                                    <td class="label">{{ __('Amount Due') }} :</td>
                                    <td class="value">{{ currency($invoice->amount_due) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h5 class="mb-3">{{ __('Payment History') }}</h5>
                <div class="payment-details-section mb-4">
                    @if ($invoice->payments->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Payment Type') }}</th>
                                        <th>{{ __('Method') }}</th>
                                        <th>{{ __('Account') }}</th>
                                        <th>{{ __('Note') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->payments->sortBy('created_at') as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('d M Y') }}</td>
                                            <td>{{ currency($payment->amount) }}</td>
                                            <td>{{ ucwords(str_replace('_', ' ', $payment->payment_type)) }}</td>
                                            <td>{{ $payment->method ?? 'N/A' }}</td>
                                            <td>{{ $payment->account->name ?? 'N/A' }}</td>
                                            <td>{{ $payment->note ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">{{ __('No payments recorded for this invoice yet.') }}</p>
                    @endif
                </div>

                @if ($invoice->notes_terms)
                    <h5 class="mb-3">{{ __('Notes & Terms') }}</h5>
                    <div class="mb-4">
                        <p>{{ $invoice->notes_terms }}</p>
                    </div>
                @endif

                @if ($invoice->invoice_footer)
                    <h5 class="mb-3">{{ __('Invoice Footer') }}</h5>
                    <div class="mb-4">
                        <p>{{ $invoice->invoice_footer }}</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        /* Basic styling for invoice details, adjust as needed */
        .invoice-header-info .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .invoice-header-info .info-label {
            font-weight: bold;
            flex-basis: 40%;
        }

        .invoice-header-info .info-value {
            flex-basis: 60%;
            text-align: right;
        }

        .date-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            color: #333;
        }

        .date-box.due-overdue {
            background-color: #fdd;
            border-color: #fbc;
            color: #dc3545;
        }

        .invoice-customer-details,
        .invoice-company-details {
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 8px;
            background-color: #fdfdfd;
        }

        .invoice-customer-details h5,
        .invoice-company-details h5 {
            color: #007bff;
            margin-bottom: 10px;
        }

        .item-details-table th,
        .item-details-table td {
            vertical-align: middle;
            text-align: center;
        }

        .item-details-table th:first-child,
        .item-details-table td:first-child {
            text-align: left;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 8px 0;
            border-bottom: 1px dashed #eee;
        }

        .summary-table .label {
            font-weight: bold;
            text-align: right;
            padding-right: 15px;
        }

        .summary-table .value {
            text-align: right;
        }

        .summary-table .total-row td {
            font-size: 1.2em;
            font-weight: bold;
            border-top: 2px solid #333;
            border-bottom: none;
        }

        .payment-details-section .detail-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }

        .payment-details-section .detail-value {
            font-size: 1.1em;
            color: #000;
        }

        /* Print Specific Styles */
        @media print {
            body * {
                visibility: hidden;
            }

            .content-area,
            .content-area * {
                visibility: visible;
            }

            .content-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
            }

            .card-header>div {
                /* Hide action buttons in print */
                display: none;
            }

            .btn-group,
            .btn-primary,
            .btn-info,
            .btn-secondary {
                display: none !important;
            }

            a[href]:after {
                content: none !important;
            }

            .col-md-6 {
                width: 48% !important;
            }
        }
    </style>
@endpush
