@extends('website::pages.user.layout.app')
@section('title', __('Wishlist'))
@section('user-content')
    <div class="dashboard_content">
        <h2 class="dashboard_title">{{ __('Wishlist') }}</h2>
        <div class="overview_listing">
            <div class="table-responsive wow fadeInUp" data-wow-duration="1.5s">
                <table>
                    <thead>
                        <tr>
                            <th class="images">{{ __('image') }}</th>
                            <th class="details">{{ __('details') }}</th>
                            <th class="price">{{ __('price') }}</th>
                            <th class="status">{{ __('Purpose') }}</th>
                            <th class="action">{{ __('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wishlists as $wishlist)
                            <tr>
                                <td class="images">
                                    <img loading="lazy" src="{{ asset($wishlist->property->thumbnail_image) }}"
                                        alt="{{ $wishlist->property->title }}" class="img-fluid w-100">
                                </td>
                                <td class="details">
                                    <a class="item_title" href="property_details.html">{{ $wishlist->property->title }}</a>
                                    <p>{{ __('Posting date') }}{{ __(':') }}
                                        {{ $wishlist->property->created_at->format('M d, Y') }}
                                    </p>
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
                                    <h3>{{ currency($wishlist->property->price) }}</h3>
                                </td>
                                <td class="status">
                                    <span class="sold">{{ $wishlist->property->purpose->name }}</span>
                                </td>
                                <td class="action">
                                    <a href="{{ route('website.user.wishlist.delete', $wishlist->id) }}"><i
                                            class="far fa-trash"></i> {{ __('Delete') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($wishlists->lastPage() > 1)
                <div class="row mt_25 wow fadeInUp" data-wow-duration="1.5s">
                    <div class="col-12">
                        <div id="pagination_area">
                            {{ $wishlists->links('vendor.pagination.frontend') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
