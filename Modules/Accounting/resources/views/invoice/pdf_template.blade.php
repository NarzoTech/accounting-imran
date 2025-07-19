<!DOCTYPE html>
<html>

<head>
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        /* Basic PDF styling - keep it simple */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            /* Use a font that supports various characters for Dompdf */
            font-size: 12px;
        }

        .container {
            width: 100%;
            margin: auto;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-details,
        .customer-details,
        .company-details,
        .item-table,
        .summary-table,
        .payment-history {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .badge {
            display: inline-block;
            padding: .35em .65em;
            font-size: .75em;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
        }

        .bg-success {
            background-color: #28a745;
        }

        .bg-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>{{ __('Invoice') }} #{{ $invoice->invoice_number }}</h2>
        </div>

        <div class="invoice-details">
            <table>
                <tr>
                    <td><strong>{{ __('Invoice Date') }}:</strong>
                        {{ optional($invoice->invoice_date)->format('d M Y') }}</td>
                    <td class="text-right"><strong>{{ __('Due Date') }}:</strong>
                        {{ optional($invoice->payment_date)->format('d M Y') }}
                        @if ($invoice->is_overdue)
                            <span class="badge bg-danger">{{ __('Overdue') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>{{ __('PO/SO Number') }}:</strong> {{ $invoice->po_so_number ?? 'N/A' }}</td>
                    <td class="text-right"><strong>{{ __('Created At') }}:</strong>
                        {{ $invoice->created_at?->format('d M Y H:i A') }}</td>
                </tr>
            </table>
        </div>

        <div class="customer-details">
            <h3>{{ __('Invoice To') }}</h3>
            <p><strong>{{ $invoice->customer->name ?? 'N/A' }}</strong></p>
            <p>{{ $invoice->customer->address ?? 'N/A' }}</p>
            <p>{{ $invoice->customer->city ?? '' }}{{ $invoice->customer->state ? ', ' . $invoice->customer->state : '' }}{{ $invoice->customer->zip_code ? ' - ' . $invoice->customer->zip_code : '' }}
            </p>
            <p>{{ $invoice->customer->country ?? '' }}</p>
            <p>{{ __('Email') }}: {{ $invoice->customer->email ?? 'N/A' }}</p>
            <p>{{ __('Phone') }}: {{ $invoice->customer->phone ?? 'N/A' }}</p>
        </div>

        <div class="company-details text-right">
            <h3>{{ config('app.name', 'Your Company Name') }}</h3>
            <p>{{ config('app.company_address', 'Your Company Address') }}</p>
            <p>{{ config('app.company_email', 'Your Company Email') }}</p>
            <p>{{ config('app.company_phone', 'Your Company Phone') }}</p>
        </div>

        <h3 class="mt-4 mb-3">{{ __('Invoice Items') }}</h3>
        <div class="item-table">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('Item') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Qty') }}</th>
                        <th>{{ __('Unit') }}</th>
                        <th>{{ __('Unit Price') }}</th>
                        <th>{{ __('Amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td>{{ $item->description ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->unit ?? 'N/A' }}</td>
                            <td>{{ currency($item->price) }}</td>
                            <td>{{ currency($item->amount) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('No items found for this invoice.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="summary-table text-right">
            <table>
                <tr>
                    <td>{{ __('Subtotal') }} :</td>
                    <td>{{ currency($invoice->subtotal) }}</td>
                </tr>
                <tr>
                    <td>{{ __('Discount') }} ({{ $invoice->discount_percentage }}%) :</td>
                    <td>{{ currency($invoice->total_amount - $invoice->subtotal - $invoice->delivery_charge) }}</td>
                </tr>
                <tr>
                    <td>{{ __('Delivery Charge') }} :</td>
                    <td>{{ currency($invoice->delivery_charge) }}</td>
                </tr>
                <tr class="total-row">
                    <td>{{ __('Total') }} :</td>
                    <td>{{ currency($invoice->total_amount) }}</td>
                </tr>
                <tr>
                    <td>{{ __('Amount Paid') }} :</td>
                    <td>{{ currency($invoice->amount_paid) }}</td>
                </tr>
                <tr>
                    <td>{{ __('Amount Due') }} :</td>
                    <td>{{ currency($invoice->amount_due) }}</td>
                </tr>
            </table>
        </div>

        <h3 class="mt-4 mb-3">{{ __('Payment History') }}</h3>
        <div class="payment-history">
            @if ($invoice->payments->isNotEmpty())
                <table>
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
            @else
                <p class="text-muted">{{ __('No payments recorded for this invoice yet.') }}</p>
            @endif
        </div>

        @if ($invoice->notes_terms)
            <h3 class="mt-4 mb-3">{{ __('Notes & Terms') }}</h3>
            <div>
                <p>{{ $invoice->notes_terms }}</p>
            </div>
        @endif

        @if ($invoice->invoice_footer)
            <h3 class="mt-4 mb-3">{{ __('Invoice Footer') }}</h3>
            <div>
                <p>{{ $invoice->invoice_footer }}</p>
            </div>
        @endif

    </div>
</body>

</html>
