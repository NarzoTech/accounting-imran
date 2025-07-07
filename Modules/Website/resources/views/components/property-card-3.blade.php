<div class="single_property single_property_3">
    <div class="single_property_img">
        <img loading="lazy" src="{{ asset($property->thumbnail_image) }}" alt="img" class="img-fluid w-100">
        <a class="feature_link"
            href="{{ route('website.property') }}?purpose={{ $property->purpose->name }}">{{ __('For') }}
            {{ $property->purpose->name }}</a>
        @if ($property->is_featured == 1)
            <a class="feature_link feature" href="{{ route('website.property') }}?featured=1">{{ __('Featured') }}</a>
        @endif
        <ul class="d-flex flex-wrap">
            <li><a href="javascript:;" class="wishlist" data-id="{{ $property->id }}"> <i class="fas fa-heart"
                        aria-hidden="true"></i></a></li>
        </ul>
    </div>
    <div class="single_property_text">
        <div class="single_property_top">
            <span class="property_tags">{{ $property->type->name }}</span>
            <a class="item_title"
                href="{{ route('website.property-details', $property->slug) }}">{{ $property->title }}</a>
            <span class="property_price">{{ currency($property->price) }}</span>
            <p><i class="fas fa-map-marker-alt"></i>{{ $property->address }}, {{ $property->city->name }}</p>
            <ul class="d-flex flex-wrap">
                <li>
                    <span><img loading="lazy" src="{{ asset('website/assets/images/bad_2.png') }}" alt="img"
                            class="img-fluid w-100"></span>
                    {{ $property->number_of_bedroom }} {{ __('Beds') }}
                </li>
                <li>
                    <span><img loading="lazy" src="{{ asset('website/assets/images/bathtab_2.png') }}" alt="img"
                            class="img-fluid w-100"></span>
                    {{ $property->number_of_bathroom }} {{ __('Baths') }}
                </li>
                <li>
                    <span><img loading="lazy" src="{{ asset('website/assets/images/squre_2.png') }}" alt="img"
                            class="img-fluid w-100"></span>
                    {{ $property->area }} Sq Ft
                </li>
            </ul>
        </div>
    </div>
</div>
