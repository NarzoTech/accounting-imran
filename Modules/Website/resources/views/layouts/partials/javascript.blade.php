@php
    $isRTL = session()->has('text_direction') && session()->get('text_direction') !== 'ltr' ? 1 : 0;
@endphp
<script>
    'use strict';
    var isRTL = {{ $isRTL }}
</script>
<!--jquery library js-->
<script src="{{ asset('website/assets/js/jquery-3.7.1.min.js') }}"></script>
<!--bootstrap js-->
<script src="{{ asset('website/assets/js/bootstrap.bundle.min.js') }}"></script>
<!--font-awesome js-->
<script src="{{ asset('website/assets/js/Font-Awesome.js') }}"></script>
<!--nice-select js-->
<script src="{{ asset('website/assets/js/jquery.nice-select.min.js') }}"></script>
<!--select-2 js-->
<script src="{{ asset('website/assets/js/select2.min.js') }}"></script>
<!--slick js-->
<script src="{{ asset('website/assets/js/slick.min.js') }}"></script>
<!--marquee js-->
<script src="{{ asset('website/assets/js/jquery.marquee.min.js') }}"></script>
<!--YTPlayer js-->
<script src="{{ asset('website/assets/js/jquery.youtube-background.min.js') }}"></script>
<!--wow js-->
<script src="{{ asset('website/assets/js/wow.min.js') }}"></script>
<!--animated barfiller js-->
<script src="{{ asset('website/assets/js/animated_barfiller.js') }}"></script>
@if (str_contains(request()->route()->getName(), '.user'))
    <script src="{{ asset('website/assets/js/jquery.simple-bar-graph.min.js') }}"></script>
@endif
<!--simple-bar-graph js-->
<!--sticky sidebar js-->
<script src="{{ asset('website/assets/js/sticky_sidebar.js') }}"></script>
<!--summernote js-->
<script src="{{ asset('backend/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<!--scroll button js-->
<script src="{{ asset('website/assets/js/scroll_button.js') }}"></script>
<script src="{{ asset('global/toastr/toastr.min.js') }}"></script>
<!--main/custom js-->

<script src="{{ asset('website/assets/js/script.js') }}"></script>




@if ($setting->cookie_status == 1)
    <script src="{{ asset('user/js/cookieconsent.min.js') }}"></script>

    <script>
        window.addEventListener("load", function() {
            window.wpcc.init({
                "border": "{{ $setting->border_color }}",
                "corners": "{{ $setting->corners }}",
                "colors": {
                    "popup": {
                        "background": "{{ $setting->background_color }}",
                        "text": "{{ $setting->text_color }}",
                        "border": "{{ $setting->border_color }}"
                    },
                    "button": {
                        "background": "{{ $setting->btn_bg_color }}",
                        "text": "{{ $setting->btn_text_color }}"
                    }
                },
                "content": {
                    "href": "{{ route('privacy-policy') }}",
                    "message": "{{ $setting->message }}",
                    "link": "{{ $setting->link_text }}",
                    "button": "{{ $setting->btn_text }}"
                }
            })
        });
    </script>
@endif



@if ($setting->tawk_status == 'active')
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = '{{ $setting->livechat_script }}';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
@endif



<script>
    @session('message')
    var type = "{{ Session::get('alert-type', 'info') }}"
    switch (type) {
        case 'info':
            toastr.info("{{ $value }}");
            break;
        case 'success':
            toastr.success("{{ $value }}");
            break;
        case 'warning':
            toastr.warning("{{ $value }}");
            break;
        case 'error':
            toastr.error("{{ $value }}");
            break;
    }
    @endsession
</script>


@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
@endif



<script>
    // ajax setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    function handleError(err) {
        if (err.status === 403) {
            toastr.error(err.responseJSON?.message || "{{ __('Access denied') }}!");
        } else if (err.status === 404) {
            toastr.error("{{ __('Requested resource not found!') }}");
        } else if (err.status === 422) {
            if (err.responseJSON?.errors) {
                Object.values(err.responseJSON.errors).forEach(errorMessages => {
                    errorMessages.forEach(message => toastr.error(message));
                });
            } else {
                toastr.error(err.responseJSON?.message || "{{ __('Validation error!') }}");
            }
        } else if (err.status === 500) {
            toastr.error("{{ __('Internal server error! Please try again later.') }}");
        } else if (err.status === 401) {
            toastr.error("{{ __('Unauthorized access! Please login first.') }}");
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            toastr.error(err.responseJSON?.message || "An unexpected error occurred!");
        }
    }
</script>


@stack('scripts')
