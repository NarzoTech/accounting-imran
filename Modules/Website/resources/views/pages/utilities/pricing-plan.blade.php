@extends('website::layouts.master')
@section('title')
    {{ $seo_setting->where('page_name', 'Pricing Page')->first()->seo_title ?? '' }}
@endsection

@php
    $seo_setting = $seo_setting->where('page_name', 'Pricing Page')->first();
@endphp

@include('website::components.website-seo')

@section('website-content')
    @include('website::components.breadcrumb', ['title' => __('Pricing Plans')])


    {{-- <!--=============================
            PRICING START
        ==============================--> --}}
    <section class="pricing_area pt_95 xs_pt_75">
        <div class="container">
            <div class="row">
                @foreach ($subscriptionPlans as $plan)
                    <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1.5s">
                        <div class=" single_pricing">
                            @include('website::components.pricing-plan-details', ['plan' => $plan])
                            <a class="common_btn"
                                href="{{ route('website.checkout') }}?plan={{ $plan->id }}">{{ __('Choose This Pack') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    {{-- <!--=============================
            PRICING END
        ==============================--> --}}

    {{-- <!--=============================
            FAQ START
        ==============================--> --}}
    <section class="pricing_faq_area faq_area pt_115 xs_pt_95 pb_120 xs_pb_100">
        <div class="container">
            <div class="row justify-content-center text-align-center">
                <div class="col-xxl-6 col-lg-7 wow fadeInUp" data-wow-duration="1.5s">
                    <div class=" section_heading mb_35">
                        <h2>{{ __('Frequently Asked Questions (FAQ) On') }} {{ $setting->app_name }} </h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center wow fadeInUp" data-wow-duration="1.5s">
                <div class=" col-lg-8">
                    <div class="faq_accordion accordion accordion-flush" id="accordionFlushExample">
                        @foreach ($faqs as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button show {{ $index == 0 ? 'show' : 'collapsed' }}"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapse{{ $index }}" aria-expanded="false"
                                        aria-controls="flush-collapse{{ $index }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="flush-collapse{{ $index }}"
                                    class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">{{ $faq->answer }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <!--=============================
            FAQ END
        ==============================--> --}}
@endsection
