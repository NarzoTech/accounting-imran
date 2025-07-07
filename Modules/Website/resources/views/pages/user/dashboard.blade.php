@extends('website::pages.user.layout.app')
@section('title', __('Dashboard'))
@section('user-content')
    <div class="dashboard_content">
        <h2 class="dashboard_title">{{ __('Welcome To Your Profile') }}</h2>
        <div class="dashboard_overview">
            <div class="row">
                <div class="col-xxl-3 col-md-6 col-xl-6 wow fadeInUp" data-wow-duration="1.5s">
                    <div class="dashboard_overview_item">
                        <div class="icon">
                            <i class="far fa-list-ul"></i>
                        </div>
                        <h3> {{ $propertiesCount }} <span>{{ __('Listing Property') }}</span></h3>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6 col-xl-6 wow fadeInUp" data-wow-duration="1.5s">
                    <div class="dashboard_overview_item blue">
                        <div class="icon">
                            <i class="fas fa-spinner"></i>
                        </div>
                        <h3> {{ $pendingPropertiesCount }} <span>{{ __('Pending Property') }}</span></h3>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6 col-xl-6 wow fadeInUp" data-wow-duration="1.5s">
                    <div class="dashboard_overview_item orange">
                        <div class="icon">
                            <i class="far fa-heart"></i>
                        </div>
                        <h3> {{ $wishlist }} <span>{{ __('Wishlist') }}</span></h3>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6 col-xl-6 wow fadeInUp" data-wow-duration="1.5s">
                    <div class="dashboard_overview_item red">
                        <div class="icon">
                            <i class="far fa-star"></i>
                        </div>
                        <h3> {{ $reviewsCount }} <span>{{ __('Reviews') }}</span></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-9 col-xl-8">
                    <div class="row">
                        <div class="col-xl-12 wow fadeInRight" data-wow-duration="1.5s">
                            <div class="review_progressbar mt_25">
                                <h3>{{ __('Summary') }}</h3>
                                <div class="single_bar">
                                    <p>{{ __('Total Property') }} <span>{{ $propertiesCount }}</span></p>
                                    <div id="bar6" class="barfiller">
                                        <div class="tipWrap">
                                            <span class="tip"></span>
                                        </div>
                                        <span class="fill" data-percentage="100"></span>
                                    </div>
                                </div>


                                @php
                                    $activePropertiesCount = $propertiesCount - $pendingPropertiesCount;

                                    $activePropertiesPercentage =
                                        $propertiesCount > 0 ? ($activePropertiesCount / $propertiesCount) * 100 : 0;

                                    // pending property percentage
                                    $pendingPropertiesPercentage =
                                        $propertiesCount > 0 ? ($pendingPropertiesCount / $propertiesCount) * 100 : 0;
                                @endphp

                                <div class="single_bar">
                                    <p>{{ __('Active Property') }} <span>{{ $activePropertiesCount }}</span></p>
                                    <div id="bar7" class="barfiller">
                                        <div class="tipWrap">
                                            <span class="tip"></span>
                                        </div>
                                        <span class="fill" data-percentage="{{ $activePropertiesPercentage }}"></span>
                                    </div>
                                </div>
                                <div class="single_bar">
                                    <p>{{ __('Pending Properties') }} <span>{{ $pendingPropertiesCount }}</span></p>
                                    <div id="bar8" class="barfiller">
                                        <div class="tipWrap">
                                            <span class="tip"></span>
                                        </div>
                                        <span class="fill" data-percentage="{{ $pendingPropertiesPercentage }}"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overview_listing">
                        <div class="table-responsive wow fadeInUp" data-wow-duration="1.5s">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="images">{{ __('Images') }}</th>
                                        <th class="details">{{ __('Details') }}</th>
                                        <th class="price">{{ __('Price') }}</th>
                                        <th class="status">{{ __('Status') }}</th>
                                        <th class="action">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($properties as $property)
                                        <tr>
                                            <td class="images">
                                                <img loading="lazy" src="{{ asset($property->thumbnail_image) }}"
                                                    alt="property" class="img-fluid w-100">
                                            </td>
                                            <td class="details">
                                                <a class="item_title"
                                                    href="{{ route('website.property-details', $property->slug) }}">{{ $property->title }}</a>
                                                <p>{{ __('Posting date') }}: {{ $property->created_at->format('F d, Y') }}
                                                </p>
                                            </td>
                                            <td class="price">
                                                <h3>{{ currency($property->price) }}</h3>
                                            </td>
                                            <td class="status">
                                                @if ($property->status == 0)
                                                    <span class="pending">{{ __('Pending') }}</span>
                                                @elseif($property->status == 1)
                                                    <span class="approved">{{ __('Approved') }}</span>
                                                @endif
                                            </td>
                                            <td class="action">
                                                <a href="{{ route('website.user.property.edit', $property->id) }}"><i
                                                        class="far fa-pen"></i>
                                                    {{ __('Edit') }}</a>
                                                <a href="javascript:;" class="delete_property"
                                                    data-id="{{ $property->id }}"><i class="far fa-trash"></i>
                                                    {{ __('Delete') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($properties->lastPage() > 1)
                            <div class="row mt_25 wow fadeInUp" data-wow-duration="1.5s">
                                <div class="col-12">
                                    <div id="pagination_area">
                                        {{ $properties->links('vendor.pagination.frontend') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-4 wow fadeInRight" data-wow-duration="1.5s">
                    <div class="dashboard_overview_review">
                        <h2>{{ __('Recent Reviews') }}</h2>

                        @foreach ($recentPropertyReviews as $review)
                            <div class="single_review">
                                <div class="single_review_img">
                                    <img loading="lazy" src="{{ asset($review?->user?->image) }}" alt="img"
                                        class="img-fluid w-100">
                                </div>
                                <div class="single_review_text">
                                    <h3>{{ $review->user?->name }}
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
                                    <h6>{{ $review->created_at->format('F d, Y') }}</h6>
                                    <p>{{ $review->comment }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




@push('scripts')
    <script>
        "use strict";
        $(document).ready(function() {
            $('.delete_property').on('click', function() {
                var id = $(this).data('id');
                var url = "{{ route('website.user.property.destroy', ':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(response) {
                        handleError(response)
                    }
                });
            })
        })
    </script>
@endpush
