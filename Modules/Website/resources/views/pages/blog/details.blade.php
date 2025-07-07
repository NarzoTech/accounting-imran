@extends('website::layouts.master')

@section('title')
    {{ html_decode($blog->title) }} || {{ $seo_setting->where('page_name', 'Blog Details Page')->first()->seo_title ?? '' }}
@endsection

@php
    $tags = $blog->tags != null ? json_decode($blog->tags) : [];
    $keywords = implode(', ', array_map(fn($t) => $t->value, $tags));
@endphp

@section('meta_description', $seo_setting->where('page_name', 'Blog Details Page')->first()->seo_description ?? '')

@section('meta_keywords', $keywords)

@section('og_title', $seo_setting->where('page_name', 'Blog Details Page')->first()->seo_title ?? '')

@section('og_description', $seo_setting->where('page_name', 'Blog Details Page')->first()->seo_description ?? '')
@section('og_image', asset($blog->image))


@section('website-content')
    @include('website::components.breadcrumb', ['title' => __('Blog Details')])


    {{-- <!--=============================
        BLOG DETAILS START
    ==============================--> --}}
    <section class="blog_details pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 wow fadeInUp" data-wow-duration="1.5s">
                    <div class="blog_details_left">
                        <div class="blog_details_img_1">
                            <img loading="lazy" src="{{ asset($blog->image) }}" alt="img" class="img-fluid w-100">
                        </div>
                        <ul class="blog_details_engage d-flex flex-wrap">
                            <li>
                                <span><img loading="lazy" src="{{ asset('website') }}/assets/images/calender_2.png"
                                        alt="icon" class="img-fluid w-100"></span>
                                {{ $blog->updated_at->format('F j, Y') }}
                            </li>
                            <li>
                                <span><img loading="lazy" src="{{ asset('website') }}/assets/images/user_icon_2.png"
                                        alt="icon" class="img-fluid w-100"></span>
                                {{ __('By') }} {{ $blog->admin?->name }}
                            </li>
                            <li>
                                <span><img loading="lazy" src="{{ asset('website') }}/assets/images/massage_2.png"
                                        alt="icon" class="img-fluid w-100"></span>
                                {{ $comments->count() }} {{ __('Comment') }}
                            </li>
                        </ul>
                        <h2 class="description_title">{{ $blog->title }}</h2>
                        <div class="blog_details_description">
                            {!! $blog->description !!}
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="blog_shear_area">
                                    <div class="row">
                                        <div class="col-xl-7">
                                            <div class="blog_shear_area_left d-flex flex-wrap">
                                                <h5>{{ __('Post Tags') }}:</h5>

                                                <ul class="blog_details_tag d-flex flex-wrap">

                                                    @foreach ($tags as $tag)
                                                        <li><a class="common_btn"
                                                                href="{{ route('website.blogs') }}?tag={{ $tag->value }}">{{ $tag->value }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xl-5">
                                            <div class="blog_shear_area_right d-flex flex-wrap">
                                                <h5>{{ __('Share') }}:</h5>
                                                <ul class="d-flex flex-wrap">
                                                    <li><a href="{{ $shareLinks->facebook }}"><i class="fab fa-facebook-f"
                                                                aria-hidden="true"></i></a></li>
                                                    <li><a href="{{ $shareLinks->twitter }}"><i class="fab fa-twitter"
                                                                aria-hidden="true"></i></a></li>
                                                    <li><a href="{{ $shareLinks->linkedin }}"><i class="fab fa-linkedin-in"
                                                                aria-hidden="true"></i></a></li>
                                                    <li><a href="{{ $shareLinks->pinterest }}"><i
                                                                class="fab fa-pinterest"></i></a></li>
                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>{{ __('Comments') }} ({{ $comments->count() }})</h2>
                        @if ($comments->count() > 0)
                            @foreach ($comments as $index => $comment)
                                <div class="blog_comment d-flex flex-wrap">
                                    <div class="blog_comment_img">
                                        <img loading="lazy" src="{{ asset($setting->default_avatar) }}"
                                            alt="{{ $comment->name }}" class="img-fluid w-100">
                                    </div>
                                    <div class="blog_comment_text">
                                        <h4>{{ $comment->name }}</h4>
                                        <span>{{ $comment->created_at->format('M d, Y') }} {{ __('at') }}
                                            {{ $comment->created_at->format('h:i A') }}</span>
                                        <p>{{ $comment->comment }}</p>
                                        <a href="#blogCommentForm" data-id="{{ $comment->id }}"
                                            class="reply">{{ __('Reply') }}</a>
                                    </div>
                                </div>
                                @if ($comment->children->count() > 0)
                                    @include('website::components.comment-reply', [
                                        'replies' => $comment->children,
                                    ])
                                @endif
                            @endforeach
                        @endif

                        <form action="javascript:;" id="blogCommentForm">
                            <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                            <input type='hidden' name='parent_id' value='0'>
                            <h2>{{ __('Leave a Comment') }}</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="blog_form_input">
                                        <input type="text" placeholder="{{ __('Name') }} *" name="name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="blog_form_input">
                                        <input type="email" placeholder="{{ __('Email') }} *"name="email">
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="blog_form_input">
                                        <textarea rows="5" placeholder="{{ __('Your Comment Here') }}..." name="comment"></textarea>
                                    </div>
                                </div>
                                @if ($setting->recaptcha_status == 'active')
                                    <div class="col-xl-12 mt-4">
                                        <div class="contact_form_input mb-3">
                                            <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xl-12 mt_15">
                                    <button class="common_btn">{{ __('Submit Comment') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sticky_sidebar">
                        <div class="blog_details_right">
                            <form action="{{ route('website.blogs') }}">
                                <input type="text" placeholder="{{ __('Search') }}..." name="search">
                                <button type="submit"><i class="far fa-search"></i></button>
                            </form>
                            @if ($popular_post->count() > 0)
                                <div class="blog_details_right_header sidebar_blog">
                                    <h3>{{ __('Popular Blog') }}</h3>
                                    @foreach ($popular_post as $popular)
                                        <div class="popular_blog d-flex flex-wrap">
                                            <div class="popular_blog_img">
                                                <img loading="lazy" src="{{ asset($popular->image) }}" alt="img"
                                                    class="img-fluid w-100">
                                            </div>
                                            <div class="popular_blog_text">
                                                <p>
                                                    <span><img loading="lazy"
                                                            src="{{ asset('website') }}/assets/images/calender.png"
                                                            alt="icon" class="img-fluid w-100"></span>
                                                    {{ $popular->updated_at->format('h:i A') }}
                                                </p>
                                                <a class="item_title"
                                                    href="{{ route('website.blog-details', $popular->slug) }}">{{ $popular->title }}</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <div class="blog_details_right_header">
                                <h3>{{ __('Property Categories') }}</h3>
                                <ul class="categories_property">
                                    @foreach ($categories as $cat)
                                        <li>
                                            <a class="common_btn d-flex flex-wrap justify-content-between"
                                                href="{{ route('website.blogs') }}?category={{ $cat->slug }}">
                                                <p>{{ $cat->title }}</p>
                                                <span>({{ $cat->blogs->count() }})</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="blog_details_right_header">
                                <h3>{{ __('Popular Tags') }}</h3>
                                <ul class="blog_details_tag d-flex flex-wrap">
                                    @foreach ($popularTags as $tag)
                                        @php
                                            $tags = json_decode($tag) ?? [];
                                        @endphp
                                        @foreach ($tags as $val)
                                            <li><a class="common_btn"
                                                    href="{{ route('website.blogs') }}?tag={{ $val->value }}">
                                                    {{ $val->value }}</a>
                                            </li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <!--=============================
        BLOG DETAILS END
    ==============================--> --}}
@endsection
