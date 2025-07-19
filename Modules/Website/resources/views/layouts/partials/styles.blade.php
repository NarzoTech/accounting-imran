<link rel="stylesheet" href="{{ asset('website/assets/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/nice-select.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/slick.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/animate.css') }}">
<link rel="stylesheet" href="{{ asset('website/assets/css/animated_barfiller.css') }}">

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
