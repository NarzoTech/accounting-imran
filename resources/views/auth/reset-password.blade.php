@extends('website::layouts.master')

@section('title')
    {{ __('Reset Password') }} || {{ $setting->app_name }}
@endsection

@section('website-content')
    @include('website::components.breadcrumb', ['title' => __('Reset Password')])

    <section class="forgot_password login_area pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-md-9 col-lg-7 col-xl-6">
                    <div class="main_login_area wow fadeInUp" data-wow-duration="1.5s">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="login_text">
                                    <h4>{{ __('Reset Password') }}!</h4>
                                    <form method="POST" action="{{ route('reset-password-store', $token) }}">
                                        @csrf
                                        <div class="single_input">
                                            <label>{{ __('Email') }}</label>
                                            <input type="text" placeholder="{{ __('Email') }}" name="email"
                                                value="{{ $user->email }}">
                                        </div>
                                        <div class="single_input">
                                            <label>{{ __('Password') }}</label>
                                            <input type="password" placeholder="{{ __('Password') }}" name="password"
                                                autocomplete="off">
                                        </div>
                                        <div class="single_input">
                                            <label>{{ __('Confirm Password') }}</label>
                                            <input type="password" placeholder="{{ __('Confirm Password') }}"
                                                name="password_confirmation" autocomplete="off">
                                        </div>
                                        @if ($setting->recaptcha_status == 'active')
                                            <div class="form-group inflanar-form-input mg-top-20">
                                                <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}">
                                                </div>
                                            </div>
                                        @endif
                                        <button class="common_btn" type="submit">{{ __('Reset Password') }}</button>
                                        <a href="{{ route('login') }}"
                                            class="forgot_pass bottom-0">{{ __('Back To Login') }}</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
