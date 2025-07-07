@extends('website::layouts.master')

@section('title')
    {{ $seo_setting->seo_title ?? '' }}
@endsection
@section('meta')
    <meta name="description" content="{{ $seo_setting->seo_description ?? '' }}">
@endsection

@section('website-content')
    {{-- <!--=============================
        BREADCRUMBS START
    ==============================--> --}}
    @include('website::components.breadcrumb', ['title' => __('Property List')])
    {{-- <!--=============================
        BREADCRUMBS END
    ==============================--> --}}


    <section class="property_grid_view pb_120 xs_pb_100">
        <div class="container">
            <div class="row justify-content-center wow fadeInUp" data-wow-duration="1.5s">
                <div class=" col-xl-11 col-lg-12">
                    @include('website::components.banner-search')
                </div>
            </div>
            <div class="row mt_95 xs_mt_75">
                @foreach ($properties as $property)
                    <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1.5s">
                        @include('website::components.property-card', [
                            'property' => $property,
                        ])
                    </div>
                @endforeach
            </div>
            @if ($properties->lastPage() > 1)
                <div class="row mt_50 wow fadeInUp" data-wow-duration="1.5s">
                    <div class=" col-12">
                        <div id="pagination_area">
                            {{ $properties->links('vendor.pagination.frontend') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
