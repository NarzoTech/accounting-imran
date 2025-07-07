<footer class="footer footer_2">
    <div class="container">
        <div class="footer_left">
            <div class="row justify-content-between">
                <div class="col-xl-4 col-sm-12 col-lg-4 wow fadeInLeft" data-wow-duration="1.5s">
                    <div class="footer_description pt_120 xs_pt_100">
                        <a class="footer_logo" href="{{ route('website.home') }}">
                            <img loading="lazy" src="{{ asset($setting->footer_logo) }}" alt="logo"
                                class="img-fluid w-100">
                        </a>
                        <p>{{ $footerContentData['description'] ?? '' }}</p>
                        <ul class="d-flex flex-wrap">
                            @foreach ($socialLinks as $sLink)
                                <li><a href="{{ $sLink->link }}"><i class="{{ $sLink->icon }}"></i></a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8 pt_120 xs_pt_0 pl_105 xs_pl_15 copyright_spacing">
                    <div class="row justify-content-between">
                        @foreach ($footerMenu as $index => $footer)
                            <div class="col-xl-3 col-sm-6 col-md-4 wow fadeInLeft"
                                data-wow-duration="{{ $index == 0 ? '1s' : '1.5s' }} ">
                                <div class="footer_link">
                                    <h4>{{ $footer->label }}</h4>
                                    <ul>
                                        @foreach (menuGetBySlug($footer->slug) as $item)
                                            <li><a href="{{ url($item['link']) }}">{{ $item['label'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-xl-5 col-sm-12 col-md-4 wow fadeInRight" data-wow-duration="2s">
                            <div class="footer_link footer_subscribe">
                                <h4>{{ __('Newsletter') }}</h4>
                                <p>{{ __('Subscribe our newsletter to get the latest news & updates') }}.</p>
                                <form id="subscribeForm">
                                    <input id="subscribe_email" name="email" type="email"
                                        placeholder="{{ __('Email address here') }}">
                                    <button type="submit" id="subscribeBtn"><i id="subscribe-spinner"
                                            class="loading-icon fas fa-sync fa-spin d-none"></i> <i
                                            class="fas fa-arrow-right send-arrow"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row mt_115 xs_mt_95">
                        <div class="col-xl-12">
                            <div class="copyright_area d-flex flex-wrap justify-content-center">
                                <p>{{ $setting->copyright_text }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>


@push('scripts')
    <script>
        'use strict';

        $(document).ready(function() {
            $("#subscribeBtn").on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('newsletter-request') }}",
                    type: "post",
                    data: $('#subscribeForm').serialize(),
                    beforeSend: function() {
                        $("#subscribe-spinner").removeClass('d-none');
                        $(".send-arrow").addClass('d-none');
                        $("#subscribeBtn").addClass('custom-opacity').attr('disabled',
                            true);
                    },
                    success: function(response) {
                        if (response) {
                            $("#subscribeForm").trigger("reset");
                            toastr.success(response.message)
                        }
                    },
                    error: function(response) {
                        if (response.responseJSON.errors.email) {
                            toastr.error(response.responseJSON.errors.email[0])
                        }
                    },
                    complete: function() {
                        $("#subscribe-spinner").addClass('d-none')
                        $(".send-arrow").removeClass('d-none');
                        $("#subscribeBtn").removeClass('custom-opacity')
                        $("#subscribeBtn").attr('disabled', false);
                    }
                });
            })

        })
    </script>
@endpush
