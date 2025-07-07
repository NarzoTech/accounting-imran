@extends('website::layouts.master')

@section('title')
    {{ $seo_setting->seo_title ?? '' }}
@endsection
@include('website::components.website-seo')
@section('og_image', asset($property->banner_image))

@section('website-content')
    {{-- <!--=============================
        BREADCRUMBS START
    ==============================--> --}}
    @include('website::components.breadcrumb', ['title' => 'Property Details'])


    {{-- <!--=============================
        PROPERTY DETAILS START
    ==============================--> --}}
    <section class="property_details pt_120 xs_pt_100 pb_105 xs_pb_85">
        <div class="container">
            <div class="property_details_slider">
                <div class="row wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-12">
                        <div class="property_details_large_img">
                            <img loading="lazy" src="{{ asset($property->banner_image) }}" class="img-fluid w-100"
                                alt="{{ $property->title }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt_50">
                <div class="col-lg-8">
                    <div class="single_property_details wow fadeInUp" data-wow-duration="1.5s">
                        <div class=" d-flex flex-wrap justify-content-between">
                            <h4>{{ $property->title }}</h4>
                            <ul class="property_details_share d-flex flex-wrap">
                                <li><a href="javascript:;" class="wishlist" data-id="{{ $property->id }}"><i
                                            class="fas fa-heart"></i></a></li>
                            </ul>
                        </div>
                        <div class="property_details_address d-flex flex-wrap justify-content-between">
                            <ul class="d-flex flex-wrap">
                                <li><i class="fas fa-map-marker-alt"></i>{{ $property->address }},
                                    {{ $property->city->name }}</li>
                                <li><i class="far fa-clock"></i>{{ $property->created_at->diffForHumans() }}</li>
                                <li><span>{{ __('For') }} {{ $property->purpose->name }}</span></li>
                            </ul>
                            <h3>{{ currency($property->price) }}</h3>
                        </div>
                        <ul class="flat_details d-flex flex-wrap">
                            <li>
                                <span><img loading="lazy" src="{{ asset('website/assets/images/bad.png') }}" alt="img"
                                        class="img-fluid w-100"></span>
                                {{ $property->number_of_bedroom }} {{ __('Beds') }}
                            </li>
                            <li>
                                <span><img loading="lazy" src="{{ asset('website/assets/images/bathtab.png') }}"
                                        alt="img" class="img-fluid w-100"></span>
                                {{ $property->number_of_bathroom }} {{ __('Baths') }}
                            </li>
                            <li>
                                <span><img loading="lazy" src="{{ asset('website/assets/images/squre.png') }}"
                                        alt="img" class="img-fluid w-100"></span>
                                {{ $property->area }} {{ __('Sq Ft') }}
                            </li>
                        </ul>
                    </div>
                    <div class="single_property_details single_property_description mt_25 wow fadeInUp"
                        data-wow-duration="1.5s">
                        <h4 class="description_title">{{ __('Description') }}</h4>
                        {!! $property->description !!}
                    </div>
                    <div class="single_property_details mt_25 wow fadeInUp" data-wow-duration="1.5s">
                        <h2>{{ __('Property Details') }}</h2>
                        <ul class=" property_apartment_details d-flex flex-wrap mt_10">
                            <li>
                                <p>{{ __('Property ID:') }}<span>{{ $property->property_id }}</span></p>
                            </li>
                            <li>
                                <p>{{ __('Rooms:') }}<span>{{ $property->number_of_room }}</span></p>
                            </li>
                            <li>
                                <p>{{ __('Property Status:') }}<span>{{ __('For') }}
                                        {{ $property->purpose->name }}</span></p>
                            </li>
                            <li>
                                <p>{{ __('Property Price:') }}<span>{{ currency($property->price) }}</span></p>
                            </li>
                            <li>
                                <p>{{ __('Garages:') }}<span>{{ $property->number_of_garage }}</span></p>
                            </li>
                            <li>
                                <p>{{ __('Bedrooms:') }}<span>{{ $property->number_of_bedroom }}</span></p>
                            </li>
                            <li>
                                <p>{{ __('Property Type:') }}<span>{{ __('Garden House') }}</span></p>
                            </li>
                            <li>
                                <p>{{ __('Baths:') }}<span>{{ $property->number_of_bathroom }}</span></p>
                            </li>
                            <li>
                                <p>{{ __('Originating Year:') }}<span>{{ $property->building_year }}</span></p>
                            </li>
                        </ul>
                        <div class="property_facilities">
                            <h4>{{ __('Facilities') }}</h4>
                            <ul class="d-flex flex-wrap">
                                @foreach ($property->amenities as $amenity)
                                    <li>{{ $amenity->title }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="single_property_details mt_25 wow fadeInUp" data-wow-duration="1.5s">
                        <h4>{{ __('Floor Plans') }}</h4>
                        <div class=" apertment_layout">
                            <img loading="lazy" src="{{ asset($property->floor_plan) }}" alt="img"
                                class="img-fluid w-100">
                        </div>
                    </div>
                    <div class="single_property_details mt_25 wow fadeInUp" data-wow-duration="1.5s">
                        <h4>{{ __('Map Location') }}</h4>
                        <div class=" apertment_map">
                            <iframe src="{{ $property->google_map_embed_code }}" width="600" height="450"
                                style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div class="single_property_details mt_25 wow fadeInUp" data-wow-duration="1.5s">
                        <h4>{{ __('Property Video') }}</h4>
                        <div class=" apertment_video">
                            @php
                                $video = $property->video_link;
                                $video = str_replace('watch?v=', 'embed/', $video);
                            @endphp
                            <iframe src="{{ $video }}" title="YouTube video player"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="single_property_details mt_25 wow fadeInUp" data-wow-duration="1.5s">
                        <h4>{{ __('Customer Reviews') }}</h4>
                        <div class="apartment_review">
                            <div class="row align-items-center">
                                <div class="col-xl-6">
                                    <div class="apartment_review_counter">
                                        <h3>{{ $property->reviews->where('rating', 5)->count() }}</h3>
                                        <p>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </p>
                                        <span>{{ $property->reviews->count() }} {{ __('(Customer Reviews)') }}</span>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    @php
                                        $totalReviews = $property->reviews->count();

                                        // Count reviews for each rating (1 to 5 stars)
                                        $ratingsCount = [
                                            5 => $property->reviews->where('rating', 5)->count(),
                                            4 => $property->reviews->where('rating', 4)->count(),
                                            3 => $property->reviews->where('rating', 3)->count(),
                                            2 => $property->reviews->where('rating', 2)->count(),
                                            1 => $property->reviews->where('rating', 1)->count(),
                                        ];

                                        // Calculate percentage for each rating
                                        $ratingsPercentage = [];
                                        foreach ($ratingsCount as $star => $count) {
                                            $ratingsPercentage[$star] =
                                                $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                        }
                                    @endphp
                                    <div class="review_progressbar">
                                        @foreach ($ratingsCount as $star => $count)
                                            <div class="single_bar">
                                                <p>{{ __("$star Star") }}</p>
                                                <div id="bar{{ $star }}" class="barfiller">
                                                    <div class="tipWrap">
                                                        <span class="tip"></span>
                                                    </div>
                                                    <span class="fill"
                                                        data-percentage="{{ round($ratingsPercentage[$star]) }}"></span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="apartment_review_area">
                            <h4>{{ $property->reviews->count() }} {{ __('Reviews') }}</h4>

                            @foreach ($property->reviews as $review)
                                <div class="single_review">
                                    <div class="single_review_img">
                                        <img loading="lazy" src="{{ asset($review->user->image) }}" alt="img"
                                            class="img-fluid w-100">
                                    </div>
                                    <div class="single_review_text">
                                        <h3>{{ $review->user->name }}
                                            <span>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </span>
                                        </h3>
                                        <h6>{{ $review->created_at->format('M d,Y') }}</h6>
                                        <p>{{ $review->comment }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @php
                        $authUser = Auth::guard('web')->user();
                    @endphp
                    @if ($authUser && $property->user_id != $authUser->id)
                        <div class="single_property_details details_apertment_form mt_25 wow fadeInUp"
                            data-wow-duration="1.5s">
                            <h4>{{ __('Leave a Review') }}</h4>
                            <form action="{{ route('website.user.review.store') }}" class="apertment_form"
                                method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="apertment_form_input">
                                            <label>{{ __('Rating') }}</label>
                                            <ul class="d-flex flex-wrap">
                                                <li data-value="1">
                                                    <i class="fas fa-star"></i>
                                                </li>
                                                <li data-value="2">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </li>
                                                <li data-value="3">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </li>
                                                <li data-value="4">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </li>
                                                <li data-value="5">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </li>
                                            </ul>

                                            <input type="hidden" name="property_id" value="{{ $property->id }}">
                                            <input type="hidden" id="rating-value" name="rating">
                                            <textarea rows="6" placeholder="Enter Massage" name="comment"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <button class="common_btn" type="submit">{{ __('Submit Review') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="sticky_sidebar">
                        <div class="property_details_sidebar">
                            <h4>{{ __('Schedule a Tour') }}</h4>
                            <form action="{{ route('website.property.contact.message') }}" class="schedule_form"
                                method="POST" id="propertyContact">
                                @csrf
                                <input type="hidden" name="property_id" value="{{ $property->id }}">
                                <div class="row">
                                    <div class="col-lg-12 col-md-6">
                                        <div class="schedule_input">
                                            <input type="date" placeholder="{{ __('Date') }}" name="date">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-6">
                                        <div class="schedule_input">
                                            <input type="time" placeholder="{{ __('Time') }}" name="time">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-6">
                                        <div class="schedule_input">
                                            <input type="text" placeholder="{{ __('Name') }}" name="name">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-6">
                                        <div class="schedule_input">
                                            <input type="text" placeholder="{{ __('Phone') }}" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="schedule_input">
                                            <input type="email" placeholder="{{ __('Email') }}" name="email">
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="schedule_input">
                                            <textarea rows="5" placeholder="{{ __('Message') }}" name="message"></textarea>
                                        </div>
                                    </div>
                                    @if ($setting->recaptcha_status == 'active')
                                        <div class="col-xl-12">
                                            <div class="wsus__contact_form_input mt_15">
                                                <div class="g-recaptcha"
                                                    data-sitekey="{{ $setting->recaptcha_site_key }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-xl-12">
                                        <div class="schedule_input">
                                            <button class="common_btn" type="submit">
                                                {{ __('Schedule-a-Tour-Form') }}
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if ($property->user && $property->user->id != null)
                            @php
                                $agent = $property->user;
                            @endphp
                            <div class="sales_executive">
                                <a href="{{ route('website.agent-details', $agent->slug) }}" class="sales_executive_img">
                                    <img loading="lazy" src="{{ $agent->image_url }}" alt="img"
                                        class="img-fluid w-100">
                                </a>
                                <a href="{{ route('website.agent-details', $agent->slug) }}"
                                    class="sales_executive_name">{{ $agent->name }}</a>
                                <p>{{ $agent->designation }}</p>
                                <ul class="d-flex flex-wrap justify-content-center">
                                    @if ($agent->facebook)
                                        <li>
                                            <a href="{{ $agent->facebook }}"><i class="fab fa-facebook-f"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                    @endif
                                    @if ($agent->twitter)
                                        <li>
                                            <a href="{{ $agent->twitter }}"><i class="fab fa-twitter"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                    @endif
                                    @if ($agent->linkedin)
                                        <li>
                                            <a href="{{ $agent->linkedin }}"><i class="fab fa-linkedin-in"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="related_property">
            <div class="container">
                <div class="row mt_115 xs_mt_95">
                    <div class="row wow fadeInUp" data-wow-duration="1.5s">
                        <div class=" col-xl-6">
                            <div class="section_heading section_heading_left mb_25">
                                <h2>{{ __('Related Properties') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row related_property_slider">
                    @foreach ($relatedProperties as $relatedProperty)
                        <div class="col-xl-4 wow fadeInUp" data-wow-duration="1.5s">
                            @include('website::components.property-card', [
                                'property' => $relatedProperty,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        'use strict';
        $(document).ready(function() {
            $('.wishlist').on('click', function() {
                // check login
                if (!"{{ auth()->check() }}") {
                    window.location.href = "{{ route('login') }}";
                    return false;
                }
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('website.user.wishlist.store') }}",
                    type: 'POST',
                    data: {
                        property_id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            })

            $(".apertment_form_input li").on('click', function() {
                var rating = $(this).data("value");
                $("#rating-value").val(rating);
                // Remove active class from all and add to selected
                $(".apertment_form_input li").removeClass("active");
                $(this).addClass("active");
            });

            // propertyContact

            $('#propertyContact').on('submit', function(e) {
                e.preventDefault();
                if ($("#g-recaptcha-response").val() === '') {
                    e.preventDefault();
                    @if ($setting->recaptcha_status == 'active')
                        toastr.error("{{ __('Please complete the recaptcha to submit the form') }}")
                        return;
                    @endif
                }
                var form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    beforeSend: function() {
                        // loader
                        $("#propertyContact button[type='submit']").html(
                            '<i class="fas fa-spinner fa-spin"></i> &nbsp; {{ __('Sending Message') }}...'
                        )
                        form.find('button[type="submit"]').prop('disabled', true);

                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            form[0].reset();
                            toastr.success(response.message);
                            form.find('button[type="submit"]').prop('disabled', false);
                            $("#propertyContact button[type='submit']").html(
                                '{{ __('Send Message') }}'
                            )
                        } else {
                            toastr.error(response.message);
                            form.find('button[type="submit"]').prop('disabled', false);
                            $("#propertyContact button[type='submit']").html(
                                '{{ __('Send Message') }}'
                            )
                        }
                    },
                    error: function(response) {
                        handleError(response)
                        form.find('button[type="submit"]').prop('disabled', false);
                        $("#propertyContact button[type='submit']").html(
                            '{{ __('Send Message') }}'
                        )
                    }
                });
            })
        })
    </script>
@endpush
