<link rel="stylesheet" href="{{ asset('website/assets/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/nice-select.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/slick.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/animate.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/animated_barfiller.css') }}">

<link rel="stylesheet" href="assets/css/all.min.css">
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/venobox.min.css">
<link rel="stylesheet" href="assets/css/slick.css">
<link rel="stylesheet" href="assets/css/scroll_button.css">
<link rel="stylesheet" href="assets/css/custom_spacing.css">
<link rel="stylesheet" href="assets/css/select2.min.css">
<link rel="stylesheet" href="assets/css/animate.css">
<link rel="stylesheet" href="assets/css/nice-select.css">
<link rel="stylesheet" href="assets/css/range_slider.css">
<link rel="stylesheet" href="assets/css/jQuery-plugin-progressbar.css">
<link rel="stylesheet" href="assets/css/pointer.css">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/responsive.css">

@if (str_contains(request()->route()->getName(), '.user'))
    <link rel="stylesheet" href="{{ asset('website/assets/css/summernote.min.css') }}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/jquery.simple-bar-graph.min.css') }}">
@endif

<link rel="stylesheet" href="{{ asset('website/assets/css/scroll_button.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/utilities.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/responsive.css') }}">
<link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">

@if (session()->has('text_direction') && session()->get('text_direction') !== 'ltr')
    <link rel="stylesheet" href="{{ asset('website/assets/css/rtl.css') }}">
@endif


@stack('css')
