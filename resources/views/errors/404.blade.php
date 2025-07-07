<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />
    <title>404 || {{ $setting->app_name }}</title>

    <link rel="icon" type="image/png" href="{{ asset('website/assets/images/favicon.png') }}">

    @include('website::layouts.partials.styles')
</head>

<body>


    {{-- <!--=============================
        ERROR START
    ==============================--> --}}
    <section class="error_area" style="background: url({{ asset('website/assets/images/error_bg.png') }});">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 wow fadeInUp" data-wow-duration="1.5s">
                    <div class="main_error">
                        <h2>404</h2>
                        <h4>{{ __("Oops! Page can't be found") }}.</h4>
                        <p>{{ __('We are really sorry but the page you requested is missing Or back to Go to homepage') }}
                        </p>
                        @php
                            // get current route
                            $route = request()->path();
                            // check if admin route
                            $is_admin = Str::contains($route, 'admin');
                        @endphp
                        <a class="common_btn"
                            href="{{ $is_admin ? route('admin.dashboard') : route('website.home') }}">{{ $is_admin ? __('Go Back Dashboard') : __('Go Back Home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <!--=============================
        ERROR END
    ==============================--> --}}


    @include('website::layouts.partials.javascript')

</body>

</html>
