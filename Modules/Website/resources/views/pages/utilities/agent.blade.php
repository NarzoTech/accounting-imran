@extends('website::layouts.master')

@section('title')
    {{ $seo_setting->seo_title ?? '' }}
@endsection
@include('website::components.website-seo')

@include('website::components.website-seo')

@section('website-content')
    {{-- <!--=============================
        BREADCRUMBS START
    ==============================--> --}}
    @include('website::components.breadcrumb', ['title' => 'Agent'])

    {{-- <!--=============================
        AGENT 2 START
    ==============================--> --}}
    <section class="agent_2 pt_115 xs_pt_95 pb_120 xs_pb_100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 wow fadeInUp" data-wow-duration="1.5s">
                    <div class="section_heading mb_25">
                        <h2>{{ __('Meet the Realty Professionals') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($agents as $agent)
                    <div class="col-xl-3 col-sm-6 col-lg-4 wow fadeInUp" data-wow-duration="1.5s">
                        @if (THEME == 1 || THEME == 3)
                            @include('website::components.agent')
                        @else
                            @include('website::components.agent_2')
                        @endif
                    </div>
                @endforeach

            </div>
            @if ($agents->lastPage() > 1)
                <div class="row mt_50 wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-12">
                        <div id="pagination_area">
                            {{ $agents->links('vendor.pagination.frontend') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    {{-- <!--=============================
        AGENT 2 END
    ==============================--> --}}
@endsection
