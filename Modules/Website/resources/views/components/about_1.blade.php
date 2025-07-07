@php
    $contentData = $content?->translation;
    $contentData = $contentData?->section_content;

    $images = json_decode($content->images ?? '[]');
@endphp
<section class="about_area pt_120 xs_pt_100">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xl-6 col-lg-6">
                <div class="row align-items-center">
                    <div class="col-xl-6 col-sm-6 wow fadeInLeft" data-wow-duration="1.5s">
                        <div class="about_area_img_1">
                            <img loading="lazy" src="{{ asset($images[0] ?? '') }}" alt="img"
                                class="img-fluid w-100">
                        </div>
                    </div>
                    <div class="col-xl-6 col-sm-6">
                        <div class="about_area_img_2 wow fadeInUp" data-wow-duration="1.5s">
                            <img loading="lazy" src="{{ asset($images[1] ?? '') }}" alt="img"
                                class="img-fluid w-100">
                        </div>
                        <div class="about_area_img_3 wow fadeInUp" data-wow-duration="1.5s">
                            <img loading="lazy" src="{{ asset($images[2] ?? '') }}" alt="img"
                                class="img-fluid w-100">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-6 wow fadeInRight" data-wow-duration="1.5s">
                <div class="about_text">
                    <div class="section_heading section_heading_left">
                        <h2>{{ $contentData['title'] }}</h2>
                    </div>
                    <p>{{ $contentData['subtitle'] }}</p>
                    <div class="about_description">
                        {!! $contentData['description'] ?? '' !!}
                    </div>
                    <a href="{{ $content->button_link }}" class="common_btn">{{ $contentData['button_text'] }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
