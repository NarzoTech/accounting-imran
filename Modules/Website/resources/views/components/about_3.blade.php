@php
    $contentData = $content?->translation;
    $contentData = $contentData?->section_content;
@endphp
<section class="about_2 mt_120 xs_mt_100">
    <div class="container">
        <div class="row">
            <div class="col-xl-5 col-md-8 col-lg-6 wow fadeInLeft" data-wow-duration="1.5s">
                <div class="about_2_img">
                    <img loading="lazy" src="{{ asset($content->image) }}" alt="about images" class="img-fluid w-100">
                    <a class="circle_box">
                        <svg viewBox="0 0 100 100">
                            <defs>
                                <path id="circle"
                                    d="
                                                                                                                                                                        M 50, 50
                                                                                                                                                                        m -37, 0
                                                                                                                                                                        a 37,37 0 1,1 74,0
                                                                                                                                                                        a 37,37 0 1,1 -74,0" />
                            </defs>
                            <text>
                                <textPath xlink:href="#circle">
                                    {{ $contentData['rotating_text'] ?? '' }}
                                </textPath>
                            </text>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="col-xl-7 col-md-12 col-lg-6 wow fadeInRight" data-wow-duration="1.5s">
                <div class="about_2_text">
                    <div class="section_heading section_heading_left mb_25">
                        <h2>{{ $contentData['title'] ?? '' }}</h2>
                    </div>
                    <p>{{ $contentData['subtitle'] ?? '' }}</p>
                    <div class="about_description">
                        {!! $contentData['description'] ?? '' !!}
                    </div>
                    @if ($contentData['button_text'] ?? '')
                        <a class="common_btn"
                            href="{{ $content->button_link ?? '' }}">{{ $contentData['button_text'] ?? '' }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
