@extends('website::pages.user.layout.app')
@section('title', __('Property List'))
@section('user-content')

    <div class="dashboard_content">
        <h2 class="dashboard_title">{{ __('My properties') }} <a class="common_btn"
                href="{{ route('website.user.property.create') }}">+ {{ __('add property') }}</a></h2>
        <div class="overview_listing wow fadeInUp" data-wow-duration="1.5s">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th class="images">{{ __('images') }}</th>
                            <th class="details">{{ __('details') }}</th>
                            <th class="price">{{ __('price') }}</th>
                            <th class="status">{{ __('Purpose') }}</th>
                            <th class="action">{{ __('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($properties as $property)
                            <tr>
                                <td class="images">
                                    <img loading="lazy" src="{{ asset($property->thumbnail_image) }}" alt="property"
                                        class="img-fluid w-100">
                                </td>
                                <td class="details">
                                    <a class="item_title"
                                        href="{{ route('website.property-details', $property->slug) }}">{{ $property->title }}</a>
                                    <p>{{ __('Posting date:') . $property->created_at->format('M d Y') }}</p>
                                    <span>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <b>{{ __('(24 Reviews)') }}</b>
                                    </span>
                                </td>
                                <td class="price">
                                    <h3>{{ currency($property->price) }}</h3>
                                </td>
                                <td class="status">
                                    <span class="sold">{{ $property->purpose->name }}</span>
                                </td>
                                <td class="action">
                                    <a href="{{ route('website.user.property.edit', $property->id) }}"><i
                                            class="far fa-pen"></i>
                                        {{ __('Edit') }}</a>

                                    <a href="javascript:;" class="delete_property" data-id="{{ $property->id }}"><i
                                            class="far fa-trash"></i> {{ __('Delete') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($properties->lastPage() > 1)
                <div class="row mt_25">
                    <div class="col-12">
                        <div id="pagination_area">
                            {{ $properties->links('vendor.pagination.frontend') }}
                        </div>
                    </div>
                </div>
            @endif
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
