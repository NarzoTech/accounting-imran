@extends('website::layouts.master')

@section('title')
    {{ $seo_setting->seo_title ?? '' }}
@endsection
@include('website::components.website-seo')

@section('website-content')
    {{-- <!--=============================
        BREADCRUMBS START
    ==============================--> --}}
    @include('website::components.breadcrumb', ['title' => __('About Us')])
    {{-- <!--=============================
        BREADCRUMBS END
    ==============================--> --}}


    {{-- <!--=============================
        ABOUT US PAGE START
    ==============================--> --}}

    @if (THEME != 3)
        @include('website::components.about_1', ['content' => $aboutSectionContent])
    @else
        @include('website::components.about_3', ['content' => $aboutSectionContent])
    @endif




    @php
        $content = $contents->where('section_name', 'amenities')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    {{-- <!--=============================
        AMENITIES START
    ==============================--> --}}
    @if ($content && $content->section_status)
        <section class="amenities_area mt_120 xs_mt_100 pt_115 xs_pt_95 pb_110 xs_pb_90">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-9 col-xl-6 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="section_heading mb_50">
                            <h2>{{ $contentData['title'] ?? '' }}</h2>
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

    @php
        $content = $contents->where('section_name', 'agent')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        <section class="agent_area pt_115 xs_pt_95">
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
                            @if (THEME == 1 || THEME == 3)
                                @include('website::components.agent')
                            @else
                                @include('website::components.agent_2')
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @php
        $content = $contents
            ->where('section_name', 'review')
            ->where('theme', THEME == 2 ? 1 : THEME)
            ->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        <section
            class="{{ THEME == 3 ? 'testimonial_2' : 'testimonial mt_120 xs_mt_100' }} pt_115 xs_pt_95 pb_120 xs_pb_100">
            @if (THEME != 3)
                @include('website::components.testimonial_2', ['quantity' => $content->quantity])
            @else
                @include('website::components.testimonial_3', ['quantity' => $content->quantity])
            @endif
        </section>
    @endif



    @php
        $content = $contents->where('section_name', 'blog')->where('page_name', 'aboutpage')->first();

        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp
    @if ($content && $content->section_status)
        <section class="blog_area pt_115 xs_pt_95">
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
                        @include('website::components.blog_' . THEME, ['blog' => $blog])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @php
        $content = $contents->where('section_name', 'brand')->first();
        $contentData = $content?->translation;
        $contentData = $contentData?->section_content;
    @endphp

    @if ($content && $content->section_status)
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
@endsection
