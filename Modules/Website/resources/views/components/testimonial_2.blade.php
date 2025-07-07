<div class="container">
    <div class="row justify-content-between align-items-end">
        <div class="col-xxl-4 col-lg-5 wow fadeInLeft" data-wow-duration="1.5s">
            <div class="section_heading section_heading_left">
                <h2>{{ $contentData['title'] ?? __('Feedback From Satisfied Customers') }}</h2>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 wow fadeInRight" data-wow-duration="1.5s">
            <p class="client_feedback_text_right">{{ __('Client Feedback') }}</p>
        </div>
    </div>
    <div class="row mt_40 slider-for">
        @foreach ($testimonials as $testimonial)
            <div class="col-12">
                <div class="testimonial_item">
                    <div class="row">
                        <div class="col-lg-4 wow fadeInLeft" data-wow-duration="1.5s">
                            <div class="testimonial_item_tetle">
                                <h4>{{ $testimonial->name }}</h4>
                                <p>{{ $testimonial->designation }}</p>
                            </div>
                        </div>
                        <div class="col-lg-8 wow fadeInRight" data-wow-duration="1.5s">
                            <div class="testimonial_description">
                                <span>
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
                                <p>{{ $testimonial->comment }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row d-none d-lg-block wow fadeInLeft" data-wow-duration="1.5s">
        <div class="col-xl-3">
            <div class="testimonial_img_area">
                <div class="row slider-nav">
                    @foreach ($testimonials->take($quantity) as $testimonial)
                        <div class="col-xl-4">
                            <div class="testimonial_img_item">
                                <img loading="lazy" src="{{ asset($testimonial->image) }}" alt="testimonail"
                                    class="img-fluid w-100">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
