@extends('website::layouts.master')

@section('title')
    {{ $seo_setting->seo_title ?? '' }}
@endsection
@include('website::components.website-seo')


@section('website-content')
    {{-- <!--=============================
                BANNER START
            ==============================--> --}}

    @php
        $slider = $contents->where('section_name', 'slider')->first();
        $sliderContent = $slider->translation;
        $sliderContent = $sliderContent->section_content;
    @endphp
    @if ($slider->section_status)
        <section class="banner_area" style="background: url('{{ asset($slider->image) }}');">
            <div class="container container_large">
                <div class="row wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-xl-11 col-xxl-9">
                        <div class="banner_contant">
                            <div class="banner_text">
                                <h1>{{ $sliderContent['title'] }}</h1>
                                <p>{{ $sliderContent['subtitle'] }}</p>
                            </div>
                            @if ($slider->search_status)
                                @include('website::components.banner-search')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
                BANNER END
            ==============================--> --}}


    {{-- <!--=============================
                ABOUT START
            ==============================--> --}}

    @php
        $content = $contents->where('section_name', 'about')->first();
    @endphp

    @if ($content && $content->section_status)
        @include('website::components.about_1')
    @endif
    {{-- <!--=============================
                ABOUT END
            ==============================--> --}}


    @php
        $content = $contents->where('section_name', 'location')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp

    @if ($content && $content->section_status)
        {{-- <!--=============================
                DESTINATION START
            ==============================--> --}}
        <section class="destination_area pt_115 xs_pt_110 pb_110 xs_pb_90">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="section_heading mb_50">
                            <h2>{{ $contentData['title'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row destination_slider">
                    @foreach ($cities->take($content->quantity) as $city)
                        <div class="col-xl-3 wow fadeInUp" data-wow-duration="1.5s">
                            <div class="single_destination">
                                <img loading="lazy" src="{{ asset($city->image) }}" alt="img" class="img-fluid w-100">
                                <div class="destination_address">
                                    <a href="{{ route('website.property') }}?location={{ $city->slug }}"><i
                                            class="far fa-arrow-right"></i></a>
                                    <div class="destination_text">
                                        <h5>{{ $city->name }}</h5>
                                        <p>{{ $city->properties->where('status', 1)->count() }} {{ __('Properties') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
                DESTINATION END
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
        <section class="property_area pt_115 xs_pt_95 pb_115 xs_pb_95">
            <div class="container">
                <div class="row justify-content-center text-align-center">
                    <div class="col-xl-6 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="section_heading mb_25">
                            <h2>{{ $contentData['title'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($properties->take($content->quantity) as $property)
                        <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1.5s">
                            @include('website::components.property-card', [
                                'property' => $property,
                            ])
                        </div>
                    @endforeach
                    <div class="text-center mt_50 wow fadeInUp" data-wow-duration="1.5s">
                        <a class="common_btn"
                            href="{{ route('website.property') }}">{{ __('Browse More Properties') }}</a>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
                PROPERTY END
            ==============================--> --}}



    @php
        $content = $contents->where('section_name', 'agent')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp

    @if ($content && $content->section_status)
        {{-- <!--=============================
                AGENT START
            ==============================--> --}}
        <section class="agent_area pt_115 xs_pt_95 pb_120 xs_pb_100">
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
                        <div class="col-xl-3 col-sm-6 col-lg-4 wow fadeInUp" data-wow-duration="1.5s">
                            @include('website::components.agent', ['agent' => $agent])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
                AGENT END
            ==============================--> --}}


    @php
        $content = $contents->where('section_name', 'video')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;

        $videoId = explode('?v=', $content->video)[1];

    @endphp

    @if ($content && $content->section_status)
        {{-- <!--=============================
                FIND STATE START
            ==============================--> --}}

        <section class="find_state" style="background: url('{{ asset($content->image) }}');">
            <div id="vbg12" data-vbg-loop="true" data-vbg="https://youtu.be/{{ $videoId }}">
            </div>
            <div class="container">
                <div class="row wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-xl-12">
                        <div class="find_state_text">
                            <h2>{{ __('Residential') }}</h2>
                            @if ($contentData['button_text'] ?? '')
                                <a href="{{ $content->button_link ?? '' }}">{{ $contentData['button_text'] ?? '' }}<i
                                        class="fas fa-arrow-right"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @php
        $content = $contents->where('section_name', 'blog')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp

    @if ($content && $content->section_status)
        <section class="blog_area pt_115 xs_pt_95 pb_120 xs_pb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="section_heading mb_15">
                            <h2>{{ $contentData['title'] ?? '' }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($blogs->take($content->quantity) as $blog)
                        @include('website::components.blog_1', ['blog' => $blog])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
                BLOG END
            ==============================--> --}}



    @php
        $content = $contents->where('section_name', 'discover_area')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp

    @if ($content && $content->section_status)
        {{-- <!--=============================
                DISCOVER START
            ==============================--> --}}
        <section class="discover_area pt_115 xs_pt_95 pb_120 xs_pb_100"
            style="background: url('{{ asset($content->image) }}');">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="section_heading mb_25">
                            <h2>{{ $contentData['title'] ?? '' }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-md-6 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="single_discover">
                            <div class="discover_img">
                                <img loading="lazy" src="{{ asset('website/') }}/assets/images/search.png" alt="icon"
                                    class="img-fluid w-100">
                            </div>
                            <a class="item_title"
                                href="{{ $content->others['section_1_link'] ?? '' }}">{{ $contentData['section_1_text'] ?? '' }}</a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="single_discover">
                            <div class="discover_img">
                                <img loading="lazy" src="{{ asset('website/') }}/assets/images/house.png" alt="icon"
                                    class="img-fluid w-100">
                            </div>
                            <a class="item_title"
                                href="{{ $content->others['section_2_link'] ?? '' }}">{{ $contentData['section_2_text'] ?? '' }}</a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="single_discover">
                            <div class="discover_img">
                                <img loading="lazy" src="{{ asset('website/') }}/assets/images/bag.png" alt="icon"
                                    class="img-fluid w-100">
                            </div>
                            <a class="item_title"
                                href="{{ $content->others['section_3_link'] ?? '' }}">{{ $contentData['section_3_text'] ?? '' }}</a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="single_discover">
                            <div class="discover_img">
                                <img loading="lazy" src="{{ asset('website/') }}/assets/images/happy.png" alt="icon"
                                    class="img-fluid w-100">
                            </div>
                            <a class="item_title"
                                href="{{ $content->others['section_4_link'] ?? '' }}">{{ $contentData['section_4_text'] ?? '' }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
                DISCOVER END
            ==============================--> --}}




    @php
        $content = $contents->where('section_name', 'brand')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp

    @if ($content && $content->section_status)
        {{-- <!--=============================
                PARTNER START
            ==============================--> --}}
        <section class="partner_area pt_60 pb_60">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-xl-12">
                        <div class="marquee_animi">
                            <ul class="single_partner">
                                @foreach ($partners as $partner)
                                    <li><a href="{{ $partner->url }}"><img loading="lazy"
                                                src="{{ asset($partner->image) }}" alt="img"
                                                class="img-fluid w-100"></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- <!--=============================
                PARTNER END
            ==============================--> --}}
@endsection
