@php
    $header_admin = Auth::guard('admin')->user();
@endphp
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

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset($setting->admin_favicon ?? $setting->favicon) }}" />

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">

    @include('admin.layouts.styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    @stack('css')


    <style>
        .template-customizer-open-btn {
            display: none !important;
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            @include('admin.layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">

                @include('admin.layouts.nav')

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->

                    <div class="container-fluid flex-grow-1 container-p-y">
                        @yield('admin-content')
                    </div>

                    <!-- Footer -->
                    @include('admin.layouts.footer')
                    <!-- / Footer -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <x-admin.delete-modal />


    @include('admin.layouts.javascripts')



</body>

</html>
