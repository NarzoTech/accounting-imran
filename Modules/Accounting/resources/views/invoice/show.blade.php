@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Invoice Details') }}</title>
@endsection
@section('admin-content')
    <div class="content-area">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-4">Invoice Details</h4>

                <div class="invoice-header-info">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="info-row">
                                <span class="info-label">Invoice Number</span>
                                <span class="info-value">#I1001</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Created At</span>
                                <span class="info-value">20:23 PM</span>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <div class="d-flex flex-column align-items-end">
                                <div class="date-box mb-2">Invoice Date: 12 Jul 2025</div>
                                <div class="date-box due">Due Date: 19 Jul 2025</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="invoice-customer-details">
                            <h5>Invoice To</h5>
                            <p>Rizvi</p>
                            <p>Address</p>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="invoice-company-details">
                            <h5>UNIQUE CARGO</h5>
                            <p>12 Nawabpur Road, Dhaka-1100</p>
                            <p>ashrafulimran@outlook.com</p>
                            <p>01883469206</p>
                        </div>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-borderless item-details-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Vat</th>
                                <th>Amount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Test</td>
                                <td></td>
                                <td>1</td>
                                <td>৳ 120.00</td>
                                <td>৳ 0.00</td>
                                <td>৳ 120.00</td>
                                <td class="amount-column">৳ 120.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end mb-4">
                    <div class="col-md-6 col-lg-4">
                        <table class="summary-table">
                            <tbody>
                                <tr>
                                    <td class="label">Subtotal :</td>
                                    <td class="value">৳ 120.00</td>
                                </tr>
                                <tr>
                                    <td class="label">Discount :</td>
                                    <td class="value">৳ 0.00</td>
                                </tr>
                                <tr class="total-row">
                                    <td class="label">Total :</td>
                                    <td class="value">৳ 120.00</td>
                                </tr>
                                <tr>
                                    <td class="label">Amount Due :</td>
                                    <td class="value">৳ 120.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h5 class="mb-3">Payment Details</h5>
                <div class="payment-details-section">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="detail-label">Date</div>
                            <div class="detail-value">07/12/2025</div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail-label">Payment Method</div>
                            <div class="detail-value">Bank Payment</div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="detail-label">Amount</div>
                            <div class="detail-value">৳ 0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backend\css\invoice-create.css') }}">
@endpush
