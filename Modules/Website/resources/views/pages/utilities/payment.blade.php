@extends('website::layouts.master')
@section('title')
    {{ $seo_setting->where('page_name', 'Pricing Page')->first()->seo_title ?? 'Payment' }}
@endsection

@php
    $seo_setting = $seo_setting->where('page_name', 'Pricing Page')->first();
@endphp

@include('website::components.website-seo')
@section('website-content')
    @include('website::components.breadcrumb', ['title' => 'Payment'])

    {{-- <!--==========================
        PAYMENT START
    ===========================--> --}}
    <section class="payment pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-md-6 wow fadeInLeft" data-wow-duration="1.5s">
                    <div class=" payment_area">
                        <h5>{{ __('how would you like to pay') }}?</h5>
                        <div class="row">
                            @foreach ($activeGateways as $gatewayKey => $gatewayDetails)
                                <div class="col-lg-4 col-6 col-md-6">
                                    <a href="javascript:;" class="single_payment place-order-btn"
                                        data-method="{{ $gatewayKey }}">
                                        <img loading="lazy" src="{{ asset($gatewayDetails['logo']) }}"
                                            alt="{{ $gatewayDetails['name'] }}" class="img-fluid w-100">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 col-lg-5 wow fadeInRight" data-wow-duration="1.5s">
                    <div class=" single_pricing mt-0">
                        @include('website::components.pricing-plan-details', ['plan' => $plan])
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        'use strict';
        var base_url = "{{ route('website.home') }}";
    </script>
    <script src="{{ asset('website/js/default/payment.js') }}"></script>
@endpush
