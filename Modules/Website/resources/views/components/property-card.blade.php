@if (THEME != 3)
    <div class=" single_property {{ THEME == 2 ? 'single_property_' . THEME : '' }}">
        <div class="single_property_img">
            <img loading="lazy" src="{{ asset($property->thumbnail_image) }}" alt="img" class="img-fluid w-100">
            <a class="feature_link"
                href="{{ route('website.property') }}?purpose={{ $property->purpose->name }}">{{ __('For') }}
                {{ $property->purpose->name }}</a>
            @if ($property->is_featured == 1)
                <a class="feature_link feature" href="{{ route('website.property') }}?featured=1">{{ __('Featured') }}</a>
            @endif
            <ul class="d-flex flex-wrap">
                <li><a href="javascript:;" class="wishlist" data-id="{{ $property->id }}"><i class="fas fa-heart"
                            aria-hidden="true"></i></a></li>
            </ul>
        </div>
        <div class="single_property_text">
            <div class="single_property_top">
                <a class="item_title"
                    href="{{ route('website.property-details', $property->slug) }}">{{ $property->title }}</a>
                <p><i class="fas fa-map-marker-alt"></i>{{ $property->address }}, {{ $property->city->name }}</p>
                <ul class="d-flex flex-wrap">
                    <li>
                        <span><img loading="lazy" src="{{ asset('website/assets/images/bad.png') }}" alt="img"
                                class="img-fluid w-100"></span>
                        {{ $property->number_of_bedroom }} {{ __('Beds') }}
                    </li>
                    <li>
                        <span><img loading="lazy" src="{{ asset('website/assets/images/bathtab.png') }}" alt="img"
                                class="img-fluid w-100"></span>
                        {{ $property->number_of_bathroom }} {{ __('Baths') }}
                    </li>
                    <li>
                        <span><img loading="lazy" src="{{ asset('website/assets/images/squre.png') }}" alt="img"
                                class="img-fluid w-100"></span>
                        {{ $property->area }} Sq Ft
                    </li>
                </ul>
            </div>
            <div class="single_property_bottom d-flex flex-wrap justify-content-between">
                <a class="read_btn"
                    href="{{ route('website.property-details', $property->slug) }}">{{ __('More Details') }}<i
                        class="fas fa-arrow-right"></i></a>
                @include('website::components.property-rating', ['property' => $property])
            </div>
            <span class="property_price">{{ currency($property->price) }}</span>
        </div>
    </div>
@else
    @include('website::components.property-card-3')
@endif



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
