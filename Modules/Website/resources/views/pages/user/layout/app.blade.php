<!DOCTYPE html>
<html lang="en">
@php
    $isRTL = session()->has('text_direction') && session()->get('text_direction') !== 'ltr' ? 1 : 0;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />
    <title>@yield('title') || {{ $setting->app_name }}</title>
    <link rel="icon" type="image/png" href="{{ asset('website/assets/images/favicon.png') }}">
    @include('website::layouts.partials.styles')
</head>

<body class="{{ THEME == 1 ? '' : 'home_' . THEME }}">
    <!--=========================
        DASHBOARD INFO START
    ==========================-->
    <section class="dashboard">
        <div class="dashboard_sidebar">
            <div class="sidebar_menu_icon">
                <i class="fas fa-bars dash_bar_icon"></i>
                <i class="far fa-times dash_close_icon"></i>
            </div>

            <a class="dashboard_sidebar_logo" href="{{ route('website.home') }}">
                <img loading="lazy" src="{{ asset($setting->logo) }}" alt="TopState" class="img-fluid w-100">
            </a>
            <div class="dashboard_sidebar_user">
                <div class="img">
                    <img loading="lazy" src="{{ asset(auth('web')->user()->imageUrl) }}" alt="dashboard"
                        class="img-fluid w-100">
                    <label for="profile_photo"><i class="far fa-camera"></i></label>
                    <form id="upload_user_avatar_form" enctype="multipart/form-data" method="POST"
                        action="{{ route('website.user.upload.user.avatar') }}">
                        @csrf
                        <input type="file" name="image" id="profile_photo" hidden
                            onchange="previewThumnailImage(event)">
                    </form>
                </div>
                <h3>{{ auth()->user()->name }}</h3>
                <p>{{ auth()->user()->address }}</p>
            </div>
            <div class="dashboard_sidebar_menu">
                <ul>
                    <li>
                        <a class="{{ Route::is('website.user.dashboard') ? 'active' : '' }}"
                            href="{{ route('website.user.dashboard') }}">
                            <span>
                                <img loading="lazy" src="{{ asset('website') }}/assets/images/dashboard_icon_1.png"
                                    alt="icon" class="img-fluid w-100">
                            </span>
                            {{ __('dashboard') }}
                        </a>
                    </li>
                    <li>
                        <a class="{{ Route::is('website.user.profile') ? 'active' : '' }}"
                            href="{{ route('website.user.profile') }}">
                            <span>
                                <img loading="lazy" src="{{ asset('website') }}/assets/images/dashboard_icon_2.png"
                                    alt="icon" class="img-fluid w-100">
                            </span>
                            {{ __('Profile') }}
                        </a>
                    </li>
                    <li>
                        <a class="{{ Route::is('website.user.property*') ? 'active' : '' }}"
                            href="{{ route('website.user.property.index') }}">
                            <span>
                                <img loading="lazy" src="{{ asset('website') }}/assets/images/dashboard_icon_3.png"
                                    alt="icon" class="img-fluid w-100">
                            </span>
                            {{ __('Property') }}
                        </a>
                    </li>
                    <li>
                        <a class="{{ Route::is('website.user.pricing.plan') ? 'active' : '' }}"
                            href="{{ route('website.user.pricing.plan') }}">
                            <span>
                                <img loading="lazy" src="{{ asset('website') }}/assets/images/dashboard_icon_4.png"
                                    alt="icon" class="img-fluid w-100">
                            </span>
                            {{ __('Pricing Plan') }}
                        </a>
                    </li>
                    <li>
                        <a class="{{ Route::is('website.user.order') || Route::is('website.user.invoice') ? 'active' : '' }}"
                            href="{{ route('website.user.order') }}">
                            <span>
                                <img loading="lazy" src="{{ asset('website') }}/assets/images/dashboard_icon_7.png"
                                    alt="icon" class="img-fluid w-100">
                            </span>
                            {{ __('order') }}
                        </a>
                    </li>
                    <li>
                        <a class="{{ Route::is('website.user.wishlist') ? 'active' : '' }}"
                            href="{{ route('website.user.wishlist') }}">
                            <span>
                                <img loading="lazy" src="{{ asset('website') }}/assets/images/dashboard_icon_6.png"
                                    alt="icon" class="img-fluid w-100">
                            </span>
                            {{ __('Wishlist') }}
                        </a>
                    </li>
                    <li>
                        <a class="{{ Route::is('website.user.reviews') ? 'active' : '' }}"
                            href="{{ route('website.user.reviews') }}">
                            <span>
                                <img loading="lazy" src="{{ asset('website') }}/assets/images/dashboard_icon_5.png"
                                    alt="icon" class="img-fluid w-100">
                            </span>
                            {{ __('Reviews') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}">
                            <span>
                                <img loading="lazy" src="{{ asset('website') }}/assets/images/dashboard_icon_8.png"
                                    alt="icon" class="img-fluid w-100">
                            </span>
                            {{ __('Logout') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        @yield('user-content')
    </section>
    <!--=========================
        DASHBOARD INFO END
    ==========================-->


    <!--================================
        SCROLL BUTTON START
    =================================-->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!--================================
        SCROLL BUTTON END
    =================================-->

    @include('website::layouts.partials.javascript')


    <script>
        "use strict";

        function previewThumnailImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview-user-avatar');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
            $("#upload_user_avatar_form").submit();
        };
    </script>


</body>

</html>
