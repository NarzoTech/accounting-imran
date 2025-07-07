<!DOCTYPE html>


<html lang="en" class="light-style layout-menu-fixed layout-compact layout-navbar-fixed"
    dir="{{ session()->has('text_direction') && session()->get('text_direction') !== 'ltr' ? 'rtl' : 'ltr' }}"
    data-theme="theme-default" data-style="light" data-assets-path="{{ asset('backend/assets') }}/"
    data-template="vertical-menu-template">


<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    @yield('title')
    <link rel="icon" href="{{ asset($setting->favicon) }}">

    <link rel="stylesheet" href="{{ asset('backend/assets/css/page-auth.css') }}">
    @include('admin.layouts.styles')

    <style>
        .template-customizer-open-btn {
            display: none !important;
        }
    </style>
</head>

<body>

    <!-- Content -->

    <div class="authentication-wrapper authentication-cover">
        <a href="" class="app-brand auth-cover-brand gap-2">
            <span class="app-brand-logo demo">
                <img src="{{ asset($setting->logo) }}" alt="{{ asset($setting->app_name) }}">
            </span>
        </a>
        @php
            $img = $setting->admin_login_image ?? 'backend/assets/img/illustrations/boy-with-rocket-light.png';
        @endphp
        <div class="authentication-inner admin_login_banner">
            <div class="row h-100">
                <div class="col-lg-7 col-xl-8 admin_login_banner_img">
                    <img src="{{ asset($img) }}" class="img-fluid" alt="Login image" width="700">
                </div>
                @yield('content')
            </div>
        </div>
    </div>

    <!-- / Content -->



    @include('admin.layouts.javascripts')

</body>

</html>
