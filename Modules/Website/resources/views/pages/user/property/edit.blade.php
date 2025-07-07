@extends('website::pages.user.layout.app')
@section('title', __('Edit Property'))
@section('user-content')

    <div class="dashboard_content">
        <h2 class="dashboard_title">{{ __('Edit property') }}
            <a class="common_btn" href="{{ route('website.user.property.index') }}">
                {{ __('All Properties') }}
            </a>
        </h2>
        <div class="dashboard_add_property">
            <form action="{{ route('website.user.property.update', $property->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="code" value="{{ getSessionLanguage() }}">
                <div class="add_property_info wow fadeInUp" data-wow-duration="1.5s">
                    <h3>{{ __('Basic Information') }}</h3>

                    <div class="row">
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('Name') }}<span class="text-danger">{{ __('*') }}</span></label>
                                <input id="name" name="title" type="text" placeholder="{{ __('Name') }}"
                                    value="{{ old('title', $property->title) }}">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="slug">{{ __('Slug') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="text" id="slug" name="slug"
                                    value="{{ old('slug', $property->slug) }}" placeholder="{{ __('Slug') }}" />
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('Property Types') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <select class="select_2" name="property_type_id">
                                    <option value="">{{ __('Select Type') }}</option>
                                    @foreach ($propertyTypes as $type)
                                        <option value="{{ $type->id }}" @selected($property->property_type_id == $type->id)>
                                            {{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('Country') }}</label>
                                <select class="select_2" name="country_id">
                                    <option value="">{{ __('Select Country') }}</option>

                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" @selected($selectedCountry?->id == $country->id)>
                                            {{ $country->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('State') }}</label>
                                <select class="select_2" name="state_id" id="state_id">
                                    <option value="">{{ __('Select State') }}</option>
                                    @foreach ($selectedCountry?->states ?? [] as $state)
                                        <option value="{{ $state->id }}" @selected($city->state_id == $state->id)>
                                            {{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('City') }}<span class="text-danger">{{ __('*') }}</span></label>
                                <select class="select_2" name="city_id" id="city_id">
                                    <option value="">{{ __('Select City') }}</option>
                                    @foreach ($selectedState ?? [] as $selectedCity)
                                        <option value="{{ $selectedCity->id }}" @selected($property->city_id == $selectedCity->id)>
                                            {{ $selectedCity->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="website">{{ __('Website') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input id="website" name="website" type="text" placeholder="{{ __('Website') }}"
                                    value="{{ old('website', $property->website) }}">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('Address') }}<span class="text-danger">{{ __('*') }}</span></label>
                                <input type="text" placeholder="{{ __('Address') }}" name="address"
                                    value="{{ old('address', $property->address) }}">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="phone">{{ __('Phone') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input id="phone" name="phone" type="text" placeholder="{{ __('Phone') }}"
                                    value="{{ old('phone', $property->phone) }}">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="email">{{ __('Email') }}</label>
                                <input type="email" placeholder="{{ __('Email') }}"
                                    value="{{ old('email', $property->email) }}" name="email">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('Purpose') }}<span class="text-danger">{{ __('*') }}</span></label>
                                <select name="property_purpose_id" id="property_purpose_id" class="select_2">
                                    <option value="">
                                        {{ __('Select Property Purpose') }}
                                    </option>
                                    @foreach ($purposes as $purpose)
                                        <option value="{{ $purpose->id }}" @selected($property->property_purpose_id == $purpose->id)>
                                            {{ $purpose->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="price">{{ __('Price') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input id="price" name="price" type="number" placeholder="{{ __('Price') }}"
                                    value="{{ old('price', $property->price) }}" step=".01">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="add_property_info wow fadeInUp" data-wow-duration="1.5s">
                    <h3>{{ __('Others Information') }}</h3>

                    <div class="row">
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="property_id">{{ __('Property ID') }}</label>
                                <input id="property_id" name="property_id" type="text"
                                    placeholder="{{ __('Property ID') }}"
                                    value="{{ old('property_id', $property->property_id) }}">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="building_year">{{ __('Building Year') }}</label>
                                <input id="building_year" name="building_year" type="text"
                                    placeholder="{{ __('Building Year') }}"
                                    value="{{ old('building_year', $property->building_year) }}">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="area">{{ __('Total Area') }} {{ __('(sft)') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input id="area" name="area" type="number"
                                    placeholder="{{ __('Total Area') }}" value="{{ old('area', $property->area) }}"
                                    required step=".01" min="0">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="number_of_room">{{ __('Total Rooms') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input id="number_of_room" name="number_of_room" type="number"
                                    placeholder="{{ __('Total Rooms') }}"
                                    value="{{ old('number_of_room', $property->number_of_room) }}" required
                                    min="0">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="number_of_bedroom">{{ __('Total Bed Rooms') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input id="number_of_bedroom" name="number_of_bedroom" type="number"
                                    placeholder="{{ __('Total Bed Rooms') }}"
                                    value="{{ old('number_of_bedroom', $property->number_of_bedroom) }}" required
                                    min="0">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="number_of_bathroom">{{ __('Total Bathrooms') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input id="number_of_bathroom" name="number_of_bathroom" type="number"
                                    placeholder="{{ __('Total Bathrooms') }}"
                                    value="{{ old('number_of_bathroom', $property->number_of_bathroom) }}" required
                                    min="0">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="number_of_garage">{{ __('Garage') }} {{ __('(sft)') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input id="number_of_garage" name="number_of_garage" type="number"
                                    placeholder="{{ __('Garage') }}"
                                    value="{{ old('number_of_garage', $property->number_of_garage) }}" required
                                    min="0" step=".01">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="number_of_unit">{{ __('Units') }}</label>
                                <input id="number_of_unit" name="number_of_unit" type="number"
                                    placeholder="{{ __('Units') }}"
                                    value="{{ old('number_of_unit', $property->number_of_unit) }}" min="0">
                            </div>
                        </div>

                    </div>

                </div>
                <div class="add_property_info wow fadeInUp" data-wow-duration="1.5s">
                    <h3>{{ __('Image And Video') }}</h3>

                    <div class="row">
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('Banner Image') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="file" name="banner_image" id="banner_image" accept="image/*">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('Thumbnail Image') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="file" name="thumbnail_image" id="thumbnail_image" accept="image/*">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('Floor Plan Image') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <input type="file" name="floor_plan" id="floor_plan" accept="image/*">
                            </div>
                        </div>
                        <div class="col-xxl-4">
                            <div class="add_property_input">
                                <label for="video_link">{{ __('Youtube Video Link') }}</label>
                                <input id="video_link" name="video_link" type="text"
                                    placeholder="{{ __('Youtube Video Link') }}"
                                    value="{{ old('video_link', $property->video_link) }}" min="0">
                            </div>
                        </div>
                        <div class="col-xxl-4">
                            <div class="add_property_input">
                                <label for="google_map_embed_code">{{ __('Map Location') }}</label>
                                <textarea name="google_map_embed_code" id="google_map_embed_code" placeholder="{{ __('Location Map') }}">{{ old('google_map_embed_code', $property->google_map_embed_code) }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="add_property_info add_property_aminities wow fadeInUp" data-wow-duration="1.5s">
                    <h3>{{ __('Description') }}</h3>

                    <div class="row">
                        <div class="col-12">
                            <div class="add_property_input">
                                <label for="description">{{ __('Description') }}<span
                                        class="text-danger">{{ __('*') }}</span></label>
                                <textarea name="description" class="summernote" id="description" placeholder="{{ __('Description') }}">{{ old('description', $property->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_property_info add_property_aminities wow fadeInUp" data-wow-duration="1.5s">
                    <h3>{{ __('Amenities') }}</h3>

                    <div class="row">
                        @foreach ($amenities as $amenity)
                            <div class="col-xxl-3 col-sm-6 col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="amenity-{{ $amenity->id }}"
                                        value="{{ $amenity->id }}" name="amenities[]"
                                        {{ in_array($amenity->id, $property_amenities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amenity-{{ $amenity->id }}">
                                        {{ $amenity->title }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="add_property_info wow fadeInUp" data-wow-duration="1.5s">
                    <h3>{{ __('Others Information') }}</h3>

                    <div class="row">
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('Featured') }}</label>
                                <select class="select_2" name="is_featured">
                                    <option value="0" @selected($property->is_featured == 0)>{{ __('No') }}</option>
                                    <option value="1" @selected($property->is_featured == 1)>{{ __('Yes') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('Top Property') }}</label>
                                <select class="select_2" name="top_property">
                                    <option value="0" @selected($property->top_property == 0)>{{ __('No') }}</option>
                                    <option value="1" @selected($property->top_property == 1)>{{ __('Yes') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label>{{ __('Urgent Property') }}</label>
                                <select class="select_2" name="urgent_property">
                                    <option value="0" @selected($property->urgent_property == 0)>{{ __('No') }}</option>
                                    <option value="1" @selected($property->urgent_property == 1)>{{ __('Yes') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6">
                            <div class="add_property_input">
                                <label for="seo_title">{{ __('Seo Title') }}</label>
                                <textarea name="seo_title" id="seo_title" placeholder="{{ __('Seo Title') }}">{{ old('seo_title', $property->seo_title) }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="add_property_input">
                                <label for="seo_description">{{ __('Seo Description') }}</label>
                                <textarea name="seo_description" id="seo_description" placeholder="{{ __('Seo Description') }}" rows="7">{!! old('seo_description', $property->seo_description) !!}</textarea>

                                <button class="common_btn" type="submit">{{ __('save') }}</button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @include('website::components.preloader')
@endsection


@push('scripts')
    <script>
        'use strict';
        $(document).ready(function() {

            $('[name="country_id"]').on('change', function() {
                $('.preloader_area').removeClass('d-none')

                var country_id = $(this).val();
                if (!country_id) {
                    toastr.error("{{ __('Please select country first') }}");
                }
                let url = "{{ route('website.get.all.states.by.country', ':id') }}";
                url = url.replace(':id', country_id);
                $.ajax({
                    url,
                    type: "get",
                    success: function(response) {
                        $('#state_id').empty();
                        $('#state_id').append(
                            '<option value="">{{ __('Select State') }}</option>');
                        $.each(response.data, function(key, value) {
                            $('#state_id').append('<option value="' + value.id + '">' +
                                value.name +
                                '</option>');
                        });
                        $('.preloader_area').addClass('d-none')
                    },
                    error: function(err) {
                        handleError(err)
                        $('.preloader_area').addClass('d-none')
                    }
                });
            })

            $('#state_id').on('change', function() {
                var state_id = $(this).val();
                if (!state_id) {
                    toastr.error("{{ __('Please select state first') }}");
                }
                $('.preloader_area').removeClass('d-none')
                let url = "{{ route('website.get.all.cities.by.state', ':id') }}";
                url = url.replace(':id', state_id);
                $.ajax({
                    url,
                    type: "get",
                    success: function(response) {
                        $('#city_id').empty();
                        $('#city_id').append(
                            '<option value="">{{ __('Select City') }}</option>');
                        $.each(response.data, function(key, value) {
                            $('#city_id').append('<option value="' + value.id + '">' +
                                value.name +
                                '</option>');
                        });
                        $('.preloader_area').addClass('d-none')
                    },
                    error: function(err) {
                        handleError(err)
                        $('.preloader_area').addClass('d-none')
                    }
                });
            })


            $('[name="title"]').on('input', function() {
                let name = $(this).val();

                var slug = convertToSlug(name);
                $("[name='slug']").val(slug);
                $('#slug-text').text(slug);
            })
        });

        function convertToSlug(text = '') {
            return text.toString().toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
        }
    </script>
@endpush
