@extends('website::layouts.master')
@section('title')
    {{ $seo_setting->where('page_name', 'FAQ Page')->first()->seo_title ?? '' }}
@endsection

@php
    $seo_setting = $seo_setting->where('page_name', 'FAQ Page')->first();
@endphp
@include('website::components.website-seo')
@section('website-content')
    @include('website::components.breadcrumb', ['title' => 'FAQ'])

    {{-- <!--=============================
        FAQ START
    ==============================--> --}}
    <section class="faq_area pt_115 xs_pt_95 pb_120 xs_pb_100">
        <div class="container">
            <div class="row justify-content-center text-align-center">
                <div class="col-xxl-6 col-lg-7 wow fadeInUp" data-wow-duration="1.5s">
                    <div class="section_heading mb_35">
                        <h2>{{ __('Frequently Asked Questions (FAQ) On') }} {{ $setting->app_name }} </h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8 wow fadeInUp" data-wow-duration="1.5s">
                    <div class="faq_accordion accordion accordion-flush" id="accordionFlushExample">
                        @foreach ($faqs as $index => $faq)
                            <div class=" accordion-item">
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
