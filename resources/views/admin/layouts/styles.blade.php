<link rel="stylesheet" href="{{ asset('backend/css/bootstrap5-toggle.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/tagify.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/fontawesome-iconpicker.min.css') }}">


<link rel="stylesheet" href="{{ asset('backend/assets/vendor/fonts/boxicons.css') }}" />

<link rel="stylesheet" href="{{ asset('backend/fontawesome/css/all.min.css') }}">

<!-- Core CSS -->
<link rel="stylesheet" href="{{ asset('backend/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ asset('backend/assets/vendor/css/theme-default.css') }}"
    class="template-customizer-theme-css" />
<link rel="stylesheet" href="{{ asset('backend/assets/css/demo.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/css/iziToast.min.css') }}">
<!-- Vendors CSS -->
<link rel="stylesheet" href="{{ asset('backend/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

<!-- Page CSS -->
<script src="{{ asset('backend/assets/vendor/libs/jquery/jquery.js') }}"></script>

<!-- Helpers -->
<script src="{{ asset('backend/assets/vendor/js/helpers.js') }}"></script>

<script src="{{ asset('backend/assets/vendor/js/template-customizer.js') }}"></script>

<script src="{{ asset('backend/assets/js/config.js') }}"></script>

<link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/nice-select.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/css/dropzone.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/css/dev.css') }}" />

@if (session()->has('text_direction') && session()->get('text_direction') === 'rtl')
    <link rel="stylesheet" href="{{ asset('backend/css/rtl.css') }}" />
@endif
