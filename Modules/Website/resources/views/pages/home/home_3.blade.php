@extends('website::layouts.master')

@section('title')
    {{ $seo_setting->seo_title ?? '' }}
@endsection
@include('website::components.website-seo')
@section('website-content')
    @php
        $content = $contents->where('section_name', 'slider')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        {{-- <!--=============================
        BANNER 3 START
    ==============================--> --}}
        <section class="banner_area banner_area_3" style="background: url('{{ asset($content->image) }}');">
            <div class="banner_area_3_overlay">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-11 wow fadeInUp" data-wow-duration="1.5s">
                            <div class="banner_contant">
                                <div class="banner_text">
                                    <h1>{{ $contentData['title'] }}</h1>
                                </div>
                                @if ($content->search_status)
                                    @include('website::components.banner-search')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
        BANNER 3 END
    ==============================--> --}}

    @php
        $content = $contents->where('section_name', 'latest_properties')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        {{-- <!--=============================
        LATEST PROPERTY START
    ==============================--> --}}
        <section class="latest_property pt_115 xs_pt_95 pb_120 xs_pb_100"
            style="background: url('{{ asset($content->image) }}');">
            <div class="container">
                <div class="row wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-xl-12">
                        <div class="section_heading section_heading_left mb_25">
                            <h2>{{ $contentData['title'] }}</h2>
                            <a class="read_btn"
                                href="{{ $content->button_link ?? route('website.property') }}">{{ $contentData['button_text'] ?? '' }}
                                <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row latest_pro_slider">
                    @foreach ($properties->take($content->quantity) as $property)
                        <div class="col-xl-4 wow fadeInUp" data-wow-duration="1.5s">
                            @include('website::components.property-card-3', ['property' => $property])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
        LATEST PROPERTY END
    ==============================--> --}}



    @php
        $content = $contents->where('section_name', 'about')->first();
    @endphp
    @if ($content && $content->section_status)
        {{-- <!--=============================
        ABOUT 2 START
    ==============================--> --}}
        @include('website::components.about_3')
    @endif
    {{-- <!--=============================
        ABOUT 2 END
    ==============================--> --}}




    @php
        $content = $contents->where('section_name', 'location')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        {{-- <!--=============================
        EXPLORE LISTING START
    ==============================--> --}}
        <section class="explore_listing mt_120 xs_mt_100 pt_115 xs_pt_95 pb_120 xs_pb_100">
            <div class="container">
                <div class="row wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-xl-12">
                        <div class="section_heading section_heading_left mb_25">
                            <h2>{{ $contentData['title'] ?? '' }}</h2>
                            <a class="read_btn" href="{{ route('website.property') }}">{{ __('Browse All Cities') }} <i
                                    class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($cities->take($content->quantity) as $city)
                        <div class="col-xl-4 col-sm-6 wow fadeInUp" data-wow-duration="1.5s">
                            <div class="explore_listing_item">
                                <a href="{{ route('website.property') }}?location={{ $city->slug }}" class="img">
                                    <img loading="lazy" src="{{ asset($city->image) }}" alt="explore"
                                        class="img-fluid w-100">
                                </a>
                                <div class="text">
                                    <a href="{{ route('website.property') }}?location={{ $city->slug }}"
                                        class="item_title">{{ $city->name }}</a>
                                    <p>
                                        <span>
                                            <img loading="lazy" src="{{ asset('website') }}/assets/images/explore_icon.png"
                                                alt="explore" class="img-fluid w-100">
                                        </span>
                                        {{ $city->properties->where('status', 1)->count() }} {{ __('Properties') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
        EXPLORE LISTING END
    ==============================--> --}}



    @php
        $content = $contents->where('section_name', 'featured_listing')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        {{-- <!--=============================
        FEATURED LISTING START
    ==============================--> --}}
        <section class="featured_listing mt_115 xs_mt_95">
            <div class="container">
                <div class="row wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-xl-12">
                        <div class="section_heading section_heading_left mb_25">
                            <h2>{{ $contentData['title'] ?? '' }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row featured_listing_slider">
                    @foreach ($featuredProperties->take($content->quantity) as $property)
                        <div class="col-xl-4 wow fadeInUp" data-wow-duration="1.5s">
                            @include('website::components.property-card-3', ['property' => $property])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
        FEATURED LISTING END
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
        <section class="enquiry mt_95 xs_mt_75" style="background: url('{{ asset($content->image) }}');">
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
        <section class="agent_area agent_area_2 pt_115 xs_pt_95">
            <div class="container">
                <div class="row wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-xl-7">
                        <div class="section_heading section_heading_left mb_25">
                            <h2>{{ $contentData['title'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($agents->take($content->quantity) as $agent)
                        <div class="col-xl-3 col-sm-6 col-lg-4 wow fadeInUp" data-wow-duration="1.5s">

                            @include('website::components.agent', ['agent' => $agent])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
        AGENT 2 END
    ==============================--> --}}


    @php
        $content = $contents->where('section_name', 'review')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        {{-- <!--=============================
        TESTIMONIAL 2 START
    ==============================--> --}}
        <section class="testimonial_2 pt_110 xs_pt_95 pb_95 xs_pb_0">
            @include('website::components.testimonial_3', ['quantity' => $content->quantity])
        </section>
    @endif
    {{-- <!--=============================
        TESTIMONIAL 2 END
    ==============================--> --}}





    @php
        $content = $contents->where('section_name', 'blog')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        <section class="blog_2 blog_3 pt_105 xs_pt_95 pb_115 xs_pb_95">
            <div class="container">
                <div class="row justify-content-center wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-xl-6">
                        <div class="section_heading mb_25">
                            <h2>{{ $contentData['title'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($blogs->take($content->quantity) as $blog)
                        @include('website::components.blog_3', ['blog' => $blog])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
