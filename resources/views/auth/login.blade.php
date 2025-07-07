@extends('website::layouts.master')

@section('title')
    {{ __('Login') }} || {{ $setting->app_name }}
@endsection

@section('website-content')
    @include('website::components.breadcrumb', ['title' => __('Login')])
    {{-- <!--=============================
        LOGIN START
    ==============================--> --}}
    <section class="login_area pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-8 col-xl-11">
                    <div class="main_login_area">
                        <div class="row">
                            <div class="col-xl-6 wow fadeInLeft" data-wow-duration="1.5s">
                                <div class=" login_text">
                                    <h4>{{ __('Login') }}</h4>
                                    <form action="{{ route('user-login') }}" method="POST">
                                        @csrf
                                        <div class="single_input">
                                            <label>{{ __('Email') }}</label>
                                            <input name="email" type="email" value="{{ old('email') }}"
                                                placeholder="{{ __('Email or Username') }}">
                                        </div>
                                        <div class="single_input">
                                            <label>{{ __('Password') }}</label>
                                            <input type="password" placeholder="{{ __('Password') }}" name="password">
                                            <span class="show_password">
                                                <i class="far fa-eye open_eye"></i>
                                                <i class="far fa-eye-slash close_eye"></i>
                                            </span>
                                        </div>
                                        <div
                                            class="single_input d-flex flex-wrap align-items-center justify-content-between">
                                            <div class="form-check">
                                                <input class="form-check-input" name="remember" type="checkbox"
                                                    value="on" @checked(old('remember')) id="flexCheckDefault">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    {{ __('Remember me') }}
                                                </label>
                                            </div>
                                            <a class="forget_password"
                                                href="{{ route('password.request') }}">{{ __('Forgot password ?') }}</a>
                                        </div>
                                        <button type="submit" @class([
                                            'common_btn',
                                            'g-recaptcha' => $setting->recaptcha_status == 'active',
                                        ])
                                            @if ($setting->recaptcha_status == 'active') data-sitekey="{{ $setting->recaptcha_site_key }}" data-callback='onSubmit'
                                                data-action='submit' @endif>{{ __('Login') }}</button>
                                    </form>
                                    @if (enum_exists('App\Enums\SocialiteDriverType'))
                                        @php
                                            $socialiteEnum = 'App\Enums\SocialiteDriverType';
                                            $icons = $socialiteEnum::getIcons();
                                        @endphp
                                        @foreach ($socialiteEnum::cases() as $index => $case)
                                            @php
                                                if ($case->value != 'google') {
                                                    continue;
                                                }
                                                $driverName = $case->value . '_login_status';
                                            @endphp
                                            @if ($setting->$driverName == 'active')
                                                <span>{{ __('Or login with') }}</span>
                                                <ul class="other_login_option d-flex flex-wrap justify-content-center">
                                                    <li>
                                                        <a href="{{ route('auth.social', $case->value) }}">
                                                            <span><img
                                                                    src="{{ asset('website/') }}/assets/images/google.png"
                                                                    alt="img" class="img-fluid w-100"></span>
                                                            {{ __('google') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            @endif
                                        @endforeach
                                    @endif
                                    <p>{{ __("Don't you have an account?") }} <a
                                            href="{{ route('register') }}">{{ __('Register') }}</a></p>
                                </div>
                            </div>
                            @php
                                $img = $setting->auth_image ?? 'website/assets/images/login_bg.jpg';
                            @endphp
                            <div class="col-xl-6 d-none d-xl-block wow fadeInRight" data-wow-duration="1.5s">
                                <div class=" login_img">
                                    <img src="{{ asset($img) }}" alt="img" class="img-fluid w-100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
