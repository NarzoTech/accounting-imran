@extends('website::layouts.master')

@section('title')
    {{ $seo_setting->seo_title ?? '' }}
@endsection
@include('website::components.website-seo')

@section('website-content')
    @include('website::layouts.partials.header_2')


    @php
        $content = $contents->where('section_name', 'slider')->first();
        $sliderContent = $content?->translation;
        $sliderContent = $sliderContent?->section_content;
        $text = text_split($sliderContent['title']);
    @endphp
    @if ($content && $content->section_status)
        {{-- <!--=============================
        BANNER 2 START
    ==============================--> --}}
        <section class="banner_2 pt_80">
            <div class="banner_area_2" style="background: url('{{ asset($content->image) }}');">
                <div class="container container_extra_large">
                    <div class="row wow fadeInUp" data-wow-duration="1.5s">
                        <div class="col-xxl-7 col-xl-9">
                            <div class="banner_2_text">
                                <h1>{{ $text[0] ?? '' }} <span>{{ $text[1] ?? '' }}</span> {{ $text[2] ?? '' }}</h1>
                                <p>{{ $sliderContent['subtitle'] }}</p>
                                <a class="common_btn_2"
                                    href="{{ $content->button_link ?? '' }}">{{ $sliderContent['button_text'] ?? '' }} <i
                                        class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @if ($content->search_status)
                        <div class="row wow fadeInUp" data-wow-duration="1.5s">
                            <div class="col-xxl-9 col-xl-11">
                                @include('website::components.banner-search')
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
        BANNER 2 END
    ==============================--> --}}



    @php
        $content = $contents->where('section_name', 'amenities')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;

    @endphp
    {{-- <!--=============================
        AMENITIES START
    ==============================--> --}}
    @if ($content && $content->section_status)
        <section class="amenities_area pt_175 xs_pt_125 pb_110 xs_pb_90">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-9 col-xl-6 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="section_heading mb_50">
                            <h2>{{ $contentData['title'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-xl-12">
                        <ul class="single_amenites d-flex flex-wrap justify-content-center">
                            @foreach ($amenities->take($content->quantity) as $amenity)
                                <li>
                                    <a href="{{ route('website.property') }}?amenity={{ $amenity->slug }}">
                                        <span><img loading="lazy" src="{{ asset($amenity->icon) }}"
                                                alt="{{ $amenity->title }}" class="img-fluid w-100"></span>
                                        {{ $amenity->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-xl-12">
                        <div class="amenities_area_btn mt_50">
                            <a class="common_btn_2"
                                href="{{ $content->button_link ?? '' }}">{{ $contentData['button_text'] ?? '' }}<i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
        AMENITIES END
    ==============================--> --}}


    @php
        $content = $contents->where('section_name', 'featured_listing')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        {{-- <!--=============================
        PROPERTY START
    ==============================--> --}}
        <section class="property_area_2 pt_115 xs_pt_95">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-9 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="section_heading mb_25">
                            <h2>{{ $contentData['title'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($featuredProperties as $property)
                        <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1.5s">
                            @include('website::components.property-card', [
                                'property' => $property,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
        PROPERTY END
    ==============================--> --}}


    @php
        $content = $contents->where('section_name', 'categories')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        {{-- <!--=============================
        CATEGORY PROPERTY START
    ==============================--> --}}
        <div class="category_property_area pt_115 xs_pt_95">
            <div class="container">
                <div class="row">
                    <div class="col-xl-9 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="section_heading section_heading_left mb_50">
                            <h2>{{ $contentData['title'] }}.</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row category_pro_slider">
                @foreach ($types as $type)
                    <div class="col-xl-3 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="category_property_item">
                            <a href="{{ route('website.property') }}?type={{ $type->slug }}"
                                class="category_property_item_img">
                                <img loading="lazy" src="{{ asset($type->image) }}" alt="{{ $type->name }}"
                                    class="img-fluid w-100">
                            </a>
                            <div class="category_property_item_text">
                                <a href="{{ route('website.property') }}?type={{ $type->slug }}"
                                    class="item_title">{{ $type->name }}</a>
                                <p>{{ $type->properties->count() }} {{ __('Properties') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    {{-- <!--=============================
        CATEGORY PROPERTY END
    ==============================--> --}}

    @php
        $content = $contents->where('section_name', 'enquiry')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        {{-- <!--=============================
        ENQUIRY START
    ==============================--> --}}
        <section class="enquiry mt_40 xs_mt_20" style="background: url('{{ asset($content->image ?? '') }}');">
            <div class="enquiry_overlay pt_120 xs_pt_100">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-xl-6 col-lg-6 wow fadeInLeft" data-wow-duration="1.5s">
                            <div class="enquiry_text">
                                <div class="section_heading section_heading_left mb_55">
                                    <h2>{{ $contentData['title'] ?? '' }}</h2>
                                </div>
                                <ul>
                                    <li>
                                        <span>01</span>
                                        <h5>{{ $contentData['subtitle_1'] ?? '' }}</h5>
                                        <p>{{ $contentData['description_1'] ?? '' }}</p>
                                    </li>
                                    <li>
                                        <span>02</span>
                                        <h5>{{ $contentData['subtitle_2'] ?? '' }}</h5>
                                        <p>{{ $contentData['description_2'] ?? '' }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-6 wow fadeInRight" data-wow-duration="1.5s">
                            @if ($content->search_status)
                                <div class="enquiry_form">
                                    <h2>{{ __('Make an Enquiry') }}</h2>
                                    @include('website::components.enquiry-form')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
        ENQUIRY END
    ==============================--> --}}

    @php
        $content = $contents->where('section_name', 'agent')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        {{-- <!--=============================
        AGENT 2 START
    ==============================--> --}}

        <section class="agent_2 pt_115 xs_pt_95">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="section_heading mb_25">
                            <h2>{{ $contentData['title'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($agents->take($content->quantity) as $agent)
                        <div class="col-xl-3 col-md-6 col-lg-4 wow fadeInUp" data-wow-duration="1.5s">
                            @include('website::components/agent_2', ['agent' => $agent])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        {{-- <!--=============================
        AGENT 2 END
    ==============================--> --}}
    @endif


    @php
        $content = $contents->where('section_name', 'review')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        {{-- <!--=============================
        TESTIMONIAL START
    ==============================--> --}}
        <section class="testimonial pt_115 xs_pt_95 pb_120 xs_pb_100 mt_120 xs_mt_100">
            @include('website::components.testimonial_2', ['quantity' => $content->quantity])
        </section>
    @endif


    @php
        $content = $contents->where('section_name', 'blog')->first();
        $contentData = $content?->translation;

        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        <section class="blog_2 pt_115 xs_pt_95 pb_120 xs_pb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="section_heading mb_15">
                            <h2>{{ $contentData['title'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($blogs->take($content->quantity) as $blog)
                        @include('website::components.blog_2', ['blog' => $blog])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- <!--=============================
        BLOG 2 END
    ==============================--> --}}
@endsection
