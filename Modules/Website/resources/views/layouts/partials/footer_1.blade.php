{{-- <!--=============================
        FOOTER START
    ==============================--> --}}
<footer class="footer">
    <div class="container container_large">
        <div class="row">
            <div class="col-xl-7">
                <div class="footer_left pt_100 pb_80">
                    <div class="row justify-content-between">
                        <div class="col-xl-5 col-sm-10 col-md-4 wow fadeInLeft" data-wow-duration="2s">
                            <div class="footer_description">
                                <a class="footer_logo" href="{{ route('website.home') }}">
                                    <img loading="lazy" src="{{ asset($setting->footer_logo) }}" alt="logo"
                                        class="img-fluid w-100">
                                </a>
                                <p>{{ $footerContentData['description'] ?? '' }}</p>
                                <ul class="d-flex flex-wrap">
                                    @foreach ($socialLinks as $sLink)
                                        <li><a href="{{ $sLink->link }}"><i class="{{ $sLink->icon }}"></i></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @foreach ($footerMenu as $index => $footer)
                            <div class="col-xl-3 col-sm-6 col-md-4 wow fadeInLeft"
                                data-wow-duration="{{ $index == 0 ? '1.5s' : '1s' }} ">
                                <div class="footer_link">
                                    <h4>{{ $footer->label }}</h4>
                                    <ul>
                                        @foreach (menuGetBySlug($footer->slug) as $item)
                                            <li><a href="{{ url($item['link']) }}">{{ $item['label'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-xl-5 wow fadeInRight" data-wow-duration="1.5s">
                <div class="footer_right">
                    <h4>{{ __('Get in Touch with Us') }}</h4>
                    <form action="{{ route('send-contact-message') }}" id="footer_contact_form" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="footer_form_input">
                                    <input type="text" placeholder="{{ __('Name') }}*" name="name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="footer_form_input">
                                    <input type="email" placeholder="{{ __('Email') }} *" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="footer_form_input">
                                    <input type="text" placeholder="{{ __('Phone Number') }}" name="phone">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="footer_form_input">
                                    <input type="text" placeholder="{{ __('Subject') }} *" name="subject">
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="footer_form_input">
                                    <textarea rows="2" placeholder="{{ __('Message') }} *" name="message"></textarea>
                                </div>
                            </div>
                            @if ($setting->recaptcha_status == 'active')
                                <div class="col-xl-12">
                                    <div class="footer_form_input mt_15">
                                        <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-xl-12">
                                <button class="common_btn footer_btn" type="submit">{{ __('Send Message') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="copy_right">
        <div class="container container_large">
            <div class="row">
                <div class="col-xl-12">
                    <div class="copyright_area d-flex flex-wrap justify-content-between">
                        <p>{{ $setting->copyright_text }}</p>
                        <ul class="d-flex flex-wrap">
                            <li><a href="{{ route('website.page', 'privacy-policy') }}">{{ __('Privacy Policy') }}</a>
                            </li>
                            <li><a
                                    href="{{ route('website.page', 'terms-contidions') }}">{{ __('Term of Service') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

{{-- <!--=============================
        FOOTER END
    ==============================--> --}}



@push('scripts')
    @include('website::components.send-mail', ['fromId' => '#footer_contact_form'])
@endpush
