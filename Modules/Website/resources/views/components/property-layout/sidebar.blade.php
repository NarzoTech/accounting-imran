<div class="property_sidebar sticky_sidebar">
    <form action="" method="GET">
        <div class="sidebar_search sidebar_wizerd">
            <h3>{{ __('Search') }}</h3>
            <div class="sidebar_search_box">
                <input type="text" placeholder="{{ __('Search') }}" name="keyword" value="{{ request()->keyword }}">
                <button type="submit"><i class="far fa-search"></i></button>
            </div>
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
                    <button class="accordion-button {{ request()->amenity ? 'active' : 'collapsed' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                        aria-controls="collapseThree">
                        {{ __('Amenities') }}
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse {{ request()->amenity ? 'show' : '' }}"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            @foreach ($amenities as $amenity)
                                <div class="col-xl-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $amenity->slug }}"
                                            name="amenity[]" id="amenity-{{ $amenity->id }}"
                                            @checked(in_array($amenity->slug, request()->amenity ?? []))>
                                        <label class="form-check-label" for="amenity-{{ $amenity->id }}">
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
