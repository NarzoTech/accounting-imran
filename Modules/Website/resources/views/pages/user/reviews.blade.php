@extends('website::pages.user.layout.app')
@section('title', __('Reviews'))
@section('user-content')
    <div class="dashboard_content">
        <h2 class="dashboard_title">{{ __('Reviews') }}</h2>
        <div class="dashboard_reviews wow fadeInUp" data-wow-duration="1.5s">
            @foreach ($reviews as $review)
                <div class="single_review">
                    <div class="single_review_img">
                        <img loading="lazy" src="{{ asset($review->user?->image) }}" alt="img" class="img-fluid w-100">
                    </div>
                    <div class="single_review_text">
                        <h3>
                            <a
                                href="{{ route('website.property-details', $review->property?->slug) }}">{{ $review->user?->name }}</a>
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
        @if ($reviews->lastPage() > 1)
            <div class="row mt_25 wow fadeInUp" data-wow-duration="1.5s">
                <div class="col-12">
                    <div id="pagination_area">
                        {{ $reviews->links('vendor.pagination.frontend') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
