@extends('website::pages.user.layout.app')
@section('title', __('Invoice'))
@section('user-content')

    <div class="dashboard_content">
        <h2 class="dashboard_title">{{ __('order invoice') }}</h2>
        <div class="dashboard_order_invoice_area mt_20">
            <a class="back_btn common_btn" href="{{ route('website.user.order') }}"><i class="far fa-long-arrow-left"></i>
                {{ __('Go Back') }} <span></span></a>
            <div class="dashboard_order_invoice wow fadeInUp" data-wow-duration="1.5s">
                <div class="dashboard_invoice_logo_area">
                    <div class="invoice_logo">
                        <img loading="lazy" src="{{ asset($setting->logo) }}" alt="logo" class="img-fluid w-100">
                    </div>
                    <div class="text">
                        <h2>{{ __('invoice') }}</h2>
                        <p>{{ __('invoice no') }} : {{ $order->order_id }}</p>
                        <p>{{ __('date') }} : {{ formattedDate(date($order->purchase_date)) }}</p>
                    </div>
                </div>
                <div class="dashboard_invoice_header">
                    <div class="text">
                        <h2>{{ __('Bill To') }}</h2>
                        <p>{{ $order->user->name }}</p>
                        <p>{{ $order->user->address }}</p>
                        <p>{{ $order->user->email }}</p>
                    </div>

                </div>
                <div class="invoice_table dashboard_order">
                    <div class="table-responsive">
                        <table>
                            <tbody>
                                <tr>
                                    <th>{{ __('Package') }}</th>
                                    <th>{{ __('Purchase Date') }}</th>
                                    <th>{{ __('Expired Date') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Payment Method') }}</th>
                                    <th>{{ __('Transaction Id') }}</th>
                                </tr>
                                <tr>
                                    <td>{{ $order->subscription->plan_name }}</td>
                                    <td>{{ formattedDate(date($order->purchase_date)) }}</td>
                                    <td>{{ formattedDate(date($order->expired_date)) }}</td>
                                    <td>{{ currency($order->amount_usd) }}</td>
                                    <td>{{ $order->payment_method }}</td>
                                    <td>{{ $order->transaction_id }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="dashboard_invoice_footer">

                    <a class="common_btn invoice_print" href="javascript:;"><i class="far fa-print"></i>
                        {{ __('Print Invoice') }}<span></span></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('website/assets/css/invoice.css') }}">
@endpush
@push('scripts')
    <script>
        "use strict";
        $(document).ready(function() {
            $('.invoice_print').on('click', function() {
                window.print();
            });
        })
    </script>
@endpush
