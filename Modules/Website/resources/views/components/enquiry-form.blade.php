<form action="{{ route('send-contact-message') }}" id="contact-form" method="POST">
    <div class="row">
        <div class="col-xl-12">
            <div class="enquiry_form_input">
                <input type="text" placeholder="{{ __('Name') }}" name="name">
                <span>
                    <img loading="lazy" src="{{ asset('website/assets/images/user_icon_black.png') }}" alt="user"
                        class="img-fluid w-100">
                </span>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="enquiry_form_input">
                <input type="text" placeholder="{{ __('Phone Number') }}" name="phone">
                <span>
                    <img loading="lazy" src="{{ asset('website/assets/images/call_icon_black.png') }}" alt="call"
                        class="img-fluid w-100">
                </span>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="enquiry_form_input">
                <input type="email" placeholder="{{ __('Email') }}" name="email">
                <span>
                    <img loading="lazy" src="{{ asset('website/assets/images/mail_icon_black.svg') }}" alt="mail"
                        class="img-fluid w-100">
                </span>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="enquiry_form_input">
                <input type="text" placeholder="{{ __('Subject') }}" name="subject">
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
        <div class="col-xl-12">
            <div class="enquiry_form_input">
                <textarea rows="4" placeholder="{{ __('Write Message') }}..." name="message"></textarea>
                <button class="common_btn" type="submit">{{ __('Send Message') }}</button>
            </div>
        </div>
    </div>
</form>


@push('scripts')
    @include('website::components.send-mail')
@endpush
