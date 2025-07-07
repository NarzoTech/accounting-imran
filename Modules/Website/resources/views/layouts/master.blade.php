<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />


    <meta name="description" content="@yield('meta_description', 'Book trusted services anytime, anywhere. From home cleaning to beauty, plumbing, and more — your one-stop on-demand service platform.')">
    <meta name="keywords" content="@yield('meta_keywords', 'on-demand services, home services, beauty services, cleaning, repair, booking services, multipurpose services')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph for Social Sharing --}}
    <meta property="og:title" content="@yield('og_title', 'Book Services On Demand')">
    <meta property="og:description" content="@yield('og_description', 'Get instant access to home cleaning, repairs, salon at home, and many more professional services.')">
    <meta property="og:image" content="@yield('og_image', asset($setting->logo))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $setting->app_name }}">


    {{-- Dynamic Page Title --}}
    <title>@yield('title', 'Multipurpose On Demand Service')</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('website/assets/images/favicon.png') }}">

    {{-- Styles --}}
    @include('website::layouts.partials.styles')

    {{-- RTL Support --}}
    @php
        $isRTL = session()->has('text_direction') && session()->get('text_direction') !== 'ltr' ? 1 : 0;
    @endphp
</head>

<body class="{{ THEME == 1 ? '' : 'home_' . THEME }}">

    <!--=============================
        MAIN MENU START
    ==============================-->

    {{-- social links --}}

    @php
        if (!cache('social_links')) {
            $socialLinks = Modules\SocialLink\app\Models\SocialLink::all();
            $socialLinks = cache()->forever('social_links', $socialLinks);
        }
        $socialLinks = cache('social_links');
    @endphp

    @include('website::layouts/partials/header_' . THEME)

    @include('website::layouts.partials.off-canvas', ['socialLinks' => $socialLinks])
    <!--=============================
        MAIN MENU END
    ==============================-->


    @yield('website-content')


    @php
        $locale = session('lang');
        $cacheKey = 'siteContent_' . $locale;

        if (!cache()->has($cacheKey)) {
            $contents = Modules\SiteAppearance\app\Models\SiteSettings::with('translations')->get();
            cache()->forever($cacheKey, $contents);
        }

        $contents = cache()->get($cacheKey);
        $footerContent = $contents->where('page_name', 'otherspage')->where('section_name', 'footer')->first();

        $footerContentData = $footerContent?->translation->section_content ?? [];
    @endphp



    @php
        if (cache()->has('footer_menu')) {
            $footerMenu = cache('footer_menu');
        } else {
            $footerMenu = Cache::rememberForever('footer_menu', function () {
                return \Modules\CustomMenu\app\Models\Menu::with('items.translations')
                    ->where('slug', 'footer-1-menu')
                    ->OrWhere('slug', 'footer-2-menu')
                    ->get();
            });
        }
    @endphp

    @if (THEME == 2)
        @include('website::layouts.partials.footer_2')
    @else
        @include('website::layouts.partials.footer_1')
    @endif




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

</body>

</html>
