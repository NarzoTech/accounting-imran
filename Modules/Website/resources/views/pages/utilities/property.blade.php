@extends('website::layouts.master')

@section('title')
    {{ $seo_setting->seo_title ?? '' }}
@endsection
@include('website::components.website-seo')

@section('website-content')
    {{-- <!--=============================
        BREADCRUMBS START
    ==============================--> --}}
    @include('website::components.breadcrumb', ['title' => __('Property List')])
    {{-- <!--=============================
        BREADCRUMBS END
    ==============================--> --}}


    {{-- <!--=============================
        PROPERTY LIST VIEW START
    ==============================--> --}}
    <section class="property_list_view pt_120 xs_pt_100 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-md-7 order-2 order-xl-0">
                    <div class="property_sidebar sticky_sidebar">
                        <form action="" method="GET">
                            <div class="sidebar_search sidebar_wizerd">
                                <h3>{{ __('search') }}</h3>
                                <input type="text" placeholder="{{ __('Search') }}" name="keyword"
                                    value="{{ request()->keyword }}">
                                <button type="submit"><i class="far fa-search"></i></button>
                            </div>
                            <div class="sidebar_dropdown sidebar_wizerd">
                                <label>{{ __('Type') }}</label>
                                <select class="select_2" name="type">
                                    <option value="">{{ __('Select Type') }}</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->slug }}" @selected(request()->type == $type->slug)>{{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sidebar_dropdown sidebar_wizerd">
                                <label>{{ __('Location') }}</label>
                                <select class="select_2" name="city">
                                    <option value="">{{ __('Select Location') }}</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->slug }}" @selected(request()->city == $city->slug)>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sidebar_dropdown sidebar_wizerd">
                                <h3>{{ __('Purpose') }}</h3>
                                <select class="select_2" name="purpose">
                                    <option value="">{{ __('Select Purpose') }}</option>
                                    @foreach ($purposes as $purpose)
                                        <option value="{{ $purpose->name }}" @selected(request()->purpose == $purpose->slug)>
                                            {{ $purpose->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="sidebar_dropdown sidebar_wizerd">
                                <label>{{ __('Price') }}</label>
                                <select class="select_2" name="price">
                                    <option value="">{{ __('Select Price') }}</option>
                                    @foreach ($priceRanges as $price)
                                        <option value="{{ $price }}" @selected(request()->price == $price)>
                                            {{ $price }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sidebar_amenities sidebar_wizerd ">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button {{ request()->amenity ? 'active' : 'collapsed' }}"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                            aria-expanded="false" aria-controls="collapseThree">
                                            {{ __('Amenities') }}
                                        </button>
                                    </h2>
                                    <div id="collapseThree"
                                        class="accordion-collapse collapse {{ request()->amenity ? 'show' : '' }}"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                @foreach ($amenities as $amenity)
                                                    <div class="col-xl-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="{{ $amenity->slug }}" name="amenity[]"
                                                                id="amenity-{{ $amenity->id }}"
                                                                @checked(in_array($amenity->slug, request()->amenity ?? []))>
                                                            <label class="form-check-label"
                                                                for="amenity-{{ $amenity->id }}">
                                                                {{ $amenity->title }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="common_btn">{{ __('Search') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-8 property_sm_margin">
                    @foreach ($properties as $property)
                        <div class="property_list_item wow fadeInUp" data-wow-duration="1.5s">
                            <div class=" row">

                                <div class="col-lg-6 col-md-5">
                                    <div class="single_property_img">
                                        <img loading="lazy" src="{{ asset($property->thumbnail_image) }}" alt="img"
                                            class="img-fluid w-100">

                                        <a class="feature_link"
                                            href="{{ route('website.property') }}?purpose={{ $property->purpose->name }}">{{ __('For') }}
                                            {{ $property->purpose->name }}</a>
                                        @if ($property->is_featured == 1)
                                            <a class="feature_link feature"
                                                href="{{ route('website.property') }}?featured=1">{{ __('Featured') }}</a>
                                        @endif
                                        <ul class="d-flex flex-wrap">
                                            <li><a href="javascript:;" class="wishlist" data-id="{{ $property->id }}"><i
                                                        class="fas fa-heart"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-7">
                                    <div class="single_property_text">
                                        <div class="single_property_top">
                                            <a class="item_title"
                                                href="{{ route('website.property-details', $property->slug) }}">{{ $property->title }}</a>
                                            <p><i class="fas fa-map-marker-alt"></i>{{ $property->address }},
                                                {{ $property->city->name }}</p>
                                            <ul class="d-flex flex-wrap">
                                                <li>
                                                    <span><img loading="lazy"
                                                            src="{{ asset('website/assets/images/bad.png') }}"
                                                            alt="img" class="img-fluid w-100"></span>
                                                    {{ $property->number_of_bedroom }} {{ __('Beds') }}
                                                </li>
                                                <li>
                                                    <span><img loading="lazy"
                                                            src="{{ asset('website/assets/images/bathtab.png') }}"
                                                            alt="img" class="img-fluid w-100"></span>
                                                    {{ $property->number_of_bathroom }} {{ __('Baths') }}
                                                </li>
                                                <li>
                                                    <span><img loading="lazy"
                                                            src="{{ asset('website/assets/images/squre.png') }}"
                                                            alt="img" class="img-fluid w-100"></span>
                                                    {{ $property->area }} Sq Ft
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="single_property_bottom d-flex flex-wrap justify-content-between">
                                            <a class="read_btn"
                                                href="{{ route('website.property-details', $property->slug) }}">{{ __('More Details') }}<i
                                                    class="fas fa-arrow-right"></i></a>
                                            @include('website::components.property-rating', [
                                                'property' => $property,
                                            ])
                                        </div>
                                        <span class="property_price">{{ currency($property->price) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

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
            </div>
        </div>
    </section>
    {{-- <!--=============================
        PROPERTY LIST VIEW END
    ==============================--> --}}
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
        })
    </script>
@endpush
