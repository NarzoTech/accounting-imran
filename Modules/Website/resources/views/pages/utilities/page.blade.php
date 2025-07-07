@extends('website::layouts.master')
@section('title')
    {{ $page->title }}
@endsection

@section('meta')
    <meta name="description" content="{{ $page->title }}">
@endsection
@section('website-content')
    @include('website::components.breadcrumb', ['title' => $page->title])


    <!--=============================
                            PRIVACY POLICY START
                        ==============================-->
    <section class="privacy_policy pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row wow fadeInUp" data-wow-duration="1.5s">
                <div class=" col-xl-12">
                    <div class="privacy_policy_area">
                        {!! $page->description !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <!--=============================
        PRIVACY POLICY END
    ==============================--> --}}
@endsection
