<div class="container">
    <div class="row wow fadeInUp" data-wow-duration="1.5s">
        <div class="col-xl-12">
            <div class="section_heading section_heading_left mb_50">
                <h2>{{ $contentData['title'] ?? __('Feedback From Satisfied Customers') }}
                </h2>
                <p>
                    @php
                        $intAverage = intval($content->others['quantity']);
                        $nextValue = $intAverage + 1;
                        $reviewPoint = $intAverage;
                        $halfReview = false;
                        if ($intAverage < $content->others['quantity'] && $content->others['quantity'] < $nextValue) {
                            $reviewPoint = $intAverage + 0.5;
                            $halfReview = true;
                        }
                    @endphp
                    <span>
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $content->others['quantity'])
                                <i class="fas fa-star"></i>
                            @elseif ($i > $reviewPoint)
                                @if ($halfReview == true)
                                    <i class="fas fa-star-half-alt"></i>
                                    @php
                                        $halfReview = false;
                                    @endphp
                                @else
                                    <i class="fal fa-star"></i>
                                @endif
                            @endif
                        @endfor
                    </span>
                    <b>{{ $content->others['quantity'] }}</b>
                    {{ __('based on user reviews') }}.
                </p>
            </div>
        </div>
    </div>
</div>
<div class="testimonial_2_area">
    <div class="row testimonial_2_slider">
        @foreach ($testimonials as $testimonial)
            <div class="col-12 wow fadeInUp" data-wow-duration="1.5s">
                <div class="testimonial_item_2">
                    <div class="row">
                        <div class="col-xxl-4 col-12 col-sm-5 col-md-5">
                            <div class="testimonial_item_2_img">
                                <img loading="lazy" src="{{ asset($testimonial->image) }}" alt="testimonial"
                                    class="img-fluid w-100">
                            </div>
                        </div>
                        <div class="col-xxl-8 col-12 col-md-7">
                            <div class="testimonial_item_2_text">
                                <span class="testimonial_2_rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $testimonial->rating)
                                            <i class="fas fa-star"></i>
                                        @elseif($i - $testimonial->rating == 0.5)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                <p class="testimonial_2_description">{{ $testimonial->comment }}</p>
                                <h3 class="testimonial_2_title">{{ $testimonial->name }}</h3>
                                <p class="testimonial_2_designation">{{ $testimonial->designation }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
