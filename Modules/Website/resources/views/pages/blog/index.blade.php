@extends('website::layouts.master')
@section('title')
    {{ $seo_setting->where('page_name', 'Blog Page')->first()->seo_title ?? '' }}
@endsection

@section('meta_description', $seo_setting->where('page_name', 'Blog Page')->first()->seo_description ?? '')
@section('meta_keywords', $seo_setting->where('page_name', 'Blog Page')->first()->seo_description ?? '')
@section('og_title', $seo_setting->where('page_name', 'Blog Page')->first()->seo_title ?? '')
@section('og_description', $seo_setting->where('page_name', 'Blog Page')->first()->seo_description ?? '')

@section('website-content')
    {{-- <!--=============================
        BREADCRUMBS START
    ==============================--> --}}
    @include('website::components.breadcrumb', ['title' => __('Blog')])
    {{-- <!--=============================
        BREADCRUMBS END
    ==============================--> --}}
    {{-- <!--=============================
        BLOG START
    ==============================--> --}}
    <section class="blog_area pt_95 xs_pt_75 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @foreach ($blogs as $blog)
                    @include('website::components.blog_' . THEME, ['blog' => $blog])
                @endforeach
            </div>
            @if ($blogs->lastPage() > 1)
                <div class="row mt_50 wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-12">
                        <div id="pagination_area">
                            {{ $blogs->links('vendor.pagination.frontend') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    {{-- <!--=============================
        BLOG END
    ==============================--> --}}
@endsection
