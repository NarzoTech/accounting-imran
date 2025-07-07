<section class="breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
    <div class="breadcrumbs_overly">
        <div class="container">
            <div class="row">
                <div class="col-12 justify-content-center">
                    <div class="breadcrumb_text wow fadeInUp" data-wow-duration="1.5s">
                        <h1>{{ $title }}</h1>
                        <ul class="d-flex flex-wrap justify-content-center">
                            <li><a href="{{ route('website.home') }}"><i class="fas fa-home"></i>{{ __('Home') }}</a>
                            </li>
                            <li><a href="javascript:;">{{ $title }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
