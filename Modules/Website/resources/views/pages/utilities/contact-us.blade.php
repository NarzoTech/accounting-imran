@extends('website::layouts.master')
@section('title')
    {{ $seo_setting->seo_title ?? '' }}
@endsection
@include('website::components.website-seo')
@section('website-content')
    {{-- <!--=============================
        BREADCRUMBS START
    ==============================--> --}}

    @include('website::components.breadcrumb', ['title' => __('Contact Us')])
    {{-- <!--=============================
        BREADCRUMBS END
    ==============================--> --}}


    {{-- <!--=============================
        CONTACT START
    ==============================--> --}}
    @php
        $contentData = $contents?->translation->section_content ?? [];

    @endphp
    <section class="contact_area pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xxl-4 col-lg-5 wow fadeInLeft" data-wow-duration="1.5s">
                    <div class="contact_address">
                        <h4>{{ $contentData['title'] ?? '' }}</h4>
                        <ul>
                            <li>
                                <span><img loading="lazy" src="{{ asset('website') }}/assets/images/location.png"
                                        alt="icon" class="img-fluid w-100"></span>
                                <div class="contact_address_text">
                                    <p>{{ __('Address') }}</p>
                                    <a class="item_title" href="javascript:;">{{ $contents->others['address'] ?? '' }}</a>
                                </div>
                            </li>
                            <li>
                                <span><img loading="lazy" src="{{ asset('website') }}/assets/images/call.png" alt="icon"
                                        class="img-fluid w-100"></span>
                                <div class="contact_address_text">
                                    <p>{{ __('Request a call back') }}</p>
                                    <a class="item_title"
                                        href="callto:{{ $contents->others['phone'] ?? '' }}">{{ $contents->others['phone'] ?? '' }}</a>
                                </div>
                            </li>
                            <li>
                                <span><img loading="lazy" src="{{ asset('website') }}/assets/images/massage_3.png"
                                        alt="icon" class="img-fluid w-100"></span>
                                <div class="contact_address_text">
                                    <p>{{ __('Email with us') }}</p>
                                    <a class="item_title"
                                        href="mailto:{{ $contents->others['email'] ?? '' }}">{{ $contents->others['email'] ?? '' }}</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xxl-7 col-lg-7 wow fadeInRight" data-wow-duration="1.5s">
                    <form action="{{ route('send-contact-message') }}" id="contact-form" method="POST">
                        <div class="row">
                            <div class="col-md-6 col-lg-12 col-xl-6">
                                <div class="contact_input">
                                    <input type="text" placeholder="{{ __('Name') }}*" name="name">
                                    <span class="contact_input_icon">
                                        <img loading="lazy" src="{{ asset('website') }}/assets/images/user_icon_3.png"
                                            alt="icon" class="img-fluid w-100">
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-12 col-xl-6">
                                <div class="contact_input">
                                    <input type="email" placeholder="{{ __('Email') }} *" name="email">
                                    <span class="contact_input_icon">
                                        <img loading="lazy" src="{{ asset('website') }}/assets/images/massage_4.png"
                                            alt="icon" class="img-fluid w-100">
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-12 col-xl-6">
                                <div class="contact_input">
                                    <input type="text" placeholder="{{ __('Phone Number') }}" name="phone">
                                    <span class="contact_input_icon">
                                        <img loading="lazy" src="{{ asset('website') }}/assets/images/call_2.png"
                                            alt="icon" class="img-fluid w-100">
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-12 col-xl-6">
                                <div class="contact_input">
                                    <input type="text" placeholder="{{ __('Subject') }} *" name="subject">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="contact_input">
                                    <textarea rows="6" placeholder="{{ __('Message') }} *" name="message"></textarea>
                                </div>
                            </div>
                            @if ($setting->recaptcha_status == 'active')
                                <div class="col-xl-12">
                                    <div class="wsus__contact_form_input mt_15">
                                        <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <div class="contact_input">
                                    <button class="common_btn" type="submit">{{ __('Send Message') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    {{-- <!--=============================
        CONTACT END
    ==============================--> --}}


    {{-- <!--=============================
        CONTACT MAP START
    ==============================--> --}}
    <section class="contact_map">
        <iframe src="{{ $contents->others['map'] ?? '' }}" width="600" height="450" style="border:0;"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>
    {{-- <!--=============================
        CONTACT MAP END
    ==============================--> --}}
@endsection



@push('scripts')
    @include('website::components.send-mail')
@endpush
