@extends('website::layouts.master')

@section('title')
    {{ $seo_setting->seo_title ?? $agent->name }}
@endsection
@section('meta')
    <meta name="description" content="{{ $seo_setting->seo_description ?? '' }}">
@endsection

@include('website::components.website-seo')

@section('og_image', asset($agent->image_url))

@section('website-content')
    {{-- <!--=============================
        BREADCRUMBS START
    ==============================--> --}}
    @include('website::components.breadcrumb', ['title' => 'Agent Profile'])
    {{-- <!--=============================
        BREADCRUMBS END
    ==============================--> --}}

    {{-- <!--=============================
        AGENT DETAILS START
    ==============================--> --}}
    <section class="agent_details pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="agent_details_area">
                <div class="row">
                    <div class="col-xl-4 col-md-7 col-lg-5 wow fadeInLeft" data-wow-duration="1.5s">
                        <div class="agent_details_area_img">
                            <img loading="lazy" src="{{ $agent->image_url }}" alt="agent" class="img-fluid w-100">
                            <div class="agent_details_img_overly">
                                <ul>
                                    @if ($agent->facebook)
                                        <li>
                                            <a href="{{ $agent->facebook }}"><i class="fab fa-facebook-f"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                    @endif
                                    @if ($agent->twitter)
                                        <li>
                                            <a href="{{ $agent->twitter }}"><i class="fab fa-twitter"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                    @endif
                                    @if ($agent->linkedin)
                                        <li>
                                            <a href="{{ $agent->linkedin }}"><i class="fab fa-linkedin-in"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-md-12 col-lg-7 wow fadeInRight" data-wow-duration="1.5s">
                        <div class="agent_details_area_text">
                            <h2>{{ $agent->name }}</h2>
                            <span>{{ $agent->designation }}</span>
                            <p><b>{{ __('Hello') }}! {{ __('This is') }} {{ $agent->name }}.</b> {{ $agent->about }}
                            </p>
                            <h5>{{ __('Personalized Information') }}</h5>
                            <ul class="agent_info d-flex flex-wrap">
                                <li>{{ __('Current Listings') }} : {{ $propertiesCount }}</li>
                                <li>{{ __('Experience Since') }} : {{ $agent->profession_start }}</li>
                                <li>{{ __('Address') }} : {{ $agent->address }}</li>
                            </ul>
                            <ul class="agent_social_media d-flex flex-wrap">
                                <li><a class="common_btn" href="emailto:{{ $agent->email }}"><i
                                            class="fas fa-envelope"></i>{{ __('Send Email') }}</a></li>
                                @if ($agent->phone)
                                    <li><a class="common_btn" href="callto:{{ $agent->phone }}"><i
                                                class="fas fa-phone-alt"></i>{{ $agent->phone }}</a>
                                    </li>
                                @endif
                                @if ($agent->whatsapp_number)
                                    <li>
                                        <a class="common_btn" href="https://wa.me/{{ $agent->whatsapp_number }}"
                                            target="_blank"><i class="fab fa-whatsapp"></i>{{ __('WhatsApp') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @if ($propertiesCount > 0)
                <div class="agent_property_list pt_115 xs_pt_95">
                    <div class="row align-items-end">
                        <div class="col-xl-6 col-sm-6 wow fadeInLeft" data-wow-duration="1.5s">
                            <div class="section_heading">
                                <h2>{{ __('Broker Properties') }}</h2>
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-6 wow fadeInRight" data-wow-duration="1.5s">
                            <div class="agent_property_quantity">
                                <p>{{ $propertiesCount }} {{ __('Properties') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt_25">
                        @foreach ($properties as $property)
                            <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1.5s">
                                @include('website::components.property-card', [
                                    'property' => $property,
                                ])
                            </div>
                        @endforeach
                    </div>
                    @if ($properties->lastPage() > 1)
                        <div class="row mt_50 wow fadeInUp" data-wow-duration="1.5s">
                            <div class="col-12">
                                <div id="pagination_area">
                                    {{ $properties->links('vendor.pagination.frontend') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
    {{-- <!--=============================
            AGENT DETAILS END
        ==============================--> --}}
@endsection
