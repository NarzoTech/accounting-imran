<div class="banner_search {{ THEME == 2 ? 'banner_2_search' : '' }}">

    <ul class="nav nav-pills" id="pills-tab" role="tablist">
        @foreach ($purposes as $index => $purpose)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $index == 0 ? 'active' : '' }}" id="pills-{{ $index }}-tab"
                    data-bs-toggle="pill" data-bs-target="#pills-{{ $index }}" type="button" role="tab"
                    aria-controls="pills-{{ $index }}" aria-selected="true">{{ $purpose->name }}</button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content" id="pills-tabContent">
        @foreach ($purposes as $index => $purpose)
            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="pills-{{ $index }}"
                role="tabpanel" aria-labelledby="pills-{{ $index }}-tab" tabindex="0">
                <form action="{{ route('website.property') }}" method="GET">
                    <input type="hidden" name="purpose" value="{{ $purpose->slug }}">
                    <ul class="d-flex flex-wrap">
                        <li>
                            @if (THEME != 2)
                                <label>{{ __('Location') }}</label>
                            @endif
                            <select class="select_2" name="city">
                                <option value="">{{ __('Select Location') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->slug }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </li>
                        <li>
                            @if (THEME != 2)
                                <label>{{ __('Type') }}</label>
                            @endif
                            <select class="select_2" name="type">
                                <option value="">{{ __('Select Type') }}</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->slug }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </li>
                        <li>
                            @if (THEME != 2)
                                <label>{{ __('Price') }}</label>
                            @endif
                            <select class="select_2" name="price">
                                <option value="">{{ __('Select Price') }}</option>
                                @foreach ($priceRanges as $price)
                                    <option value="{{ $price }}">{{ $price }}</option>
                                @endforeach
                            </select>
                        </li>
                        <li>
                            <input type="text" placeholder="{{ __('Enter Keyword') }}..." name="keyword">
                        </li>
                    </ul>
                    <button class="common_btn banner_input_btn" type="submit">{{ __('Search') }}</button>
                    <div class="adv_search_icon adv_search_icon_1"><i class="far fa-ellipsis-v"></i>
                    </div>
                    <div class="adv_search_area">
                        <div class="adv_search_close adv_search_close_1"><i class="fal fa-times"></i></div>
                        <h3>{{ __('Amenities') }}</h3>
                        <div class="row">
                            @foreach ($amenities as $amenity)
                                <div class="col-xl-2 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $amenity->slug }}"
                                            name="amenity[]" id="amenity-{{ $amenity->id }}">
                                        <label class="form-check-label" for="amenity-{{ $amenity->id }}">
                                            {{ $amenity->title }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row mt_15">
                            <div class="col-lg-3 col-sm-6">
                                <select class="select_2" name="number_of_bedroom">
                                    <option value="">{{ __('Bedroom') }}</option>
                                    @for ($i = 1; $i <= $maxBedrooms; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <select class="select_2" name="number_of_bathroom">
                                    <option value="">{{ __('Bath Room') }}</option>
                                    @for ($i = 1; $i <= $maxBathrooms; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <select class="select_2" name="building_year">
                                    <option value="">{{ __('Built Year') }}</option>
                                    @for ($i = 2010; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
