<script src="{{ asset('backend/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('backend/clockpicker/dist/bootstrap-clockpicker.js') }}"></script>
<script src="{{ asset('backend/js/boxicons.js') }}"></script>


<!-- Vendors JS -->
<script src="{{ asset('backend/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

<!-- Main JS -->

<script src="{{ asset('backend/assets/js/main.js') }}"></script>


<!-- Page JS -->
<script src="{{ asset('backend/assets/js/dashboards-analytics.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap5-toggle.jquery.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('backend/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/tagify.js') }}"></script>
<script src="{{ asset('backend/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('backend/js/sticky_sidebar.js') }}"></script>
<script src="{{ asset('backend/clockpicker/dist/bootstrap-clockpicker.js') }}"></script>
<script src="{{ asset('backend/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/fontawesome-iconpicker.min.js') }}"></script>
<script src="{{ asset('global/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('backend/js/iziToast.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
<script src="{{ asset('backend/js/dropzone.js') }}"></script>
<script src="{{ asset('backend/js/forms-file-upload.js') }}"></script>
<script src="{{ asset('backend/js/clipboard.min.js') }}"></script>
<script src="{{ asset('backend/js/custom.js') }}"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    @session('message')
    var type = "{{ Session::get('alert-type', 'info') }}"
    switch (type) {
        case 'info':
            toastr.info("{{ $value }}", '', options);
            break;
        case 'success':
            toastr.success("{{ $value }}", '', options);
            break;
        case 'warning':
            toastr.warning("{{ $value }}", '', options);
            break;
        case 'error':
            toastr.error("{{ $value }}", '', options);
            break;
    }
    @endsession
</script>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
    $('input[type="checkbox"][data-bs-toggle="toggle"]').bootstrapToggle();
</script>

<script>
    function numberOnly(str) {
        let val = str.replace(/[^0-9.]/g, '');

        return parseFloat(val)
    }

    function handleError(error) {
        console.log(error);
        if (error.responseJSON) {
            if (error.responseJSON.message) {
                toastr.error(error.responseJSON.message, '', options);
            }
            if (error.responseJSON.errors) {
                $.each(error.responseJSON.errors, function(index, data) {
                    toastr.error(data, '', options);
                })
            }

        }
    }

    function convertToSlug(text = '') {
        return text.toString().toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
    }


    $('[name="slug"]').on('input', function() {
        var slug = $(this).val();
        $('#slug-text').text(slug);
    });

    $('.generate_slug').on('click', function() {
        let name = $("[name='title']").val();

        if (!name) {
            name = $("[name='name']").val();
        }

        var slug = convertToSlug(name);
        $("[name='slug']").val(slug);
        $('#slug-text').text(slug);
    })
</script>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
@endif


<script>
    function handleStatus(route) {
        $.ajax({
            url: route,
            type: 'post',
            success: function(res) {
                toastr.success(res.message, '', options);
            },
            error: function(err) {
                handleError(err)
            }
        })
    }

    function prevImage(inputId, previewId, labelId) {
        $.uploadPreview({
            input_field: "#" + inputId,
            preview_box: "#" + previewId,
            label_field: "#" + labelId,
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
    }

    $(document).ready(function() {
        'use strict';
        $('.export').on('click', function() {
            // get full url including query string
            var fullUrl = window.location.href;
            if (fullUrl.includes('?')) {
                fullUrl += '&export=true';
            } else {
                fullUrl += '?export=true';
            }

            window.location.href = fullUrl;
        })
        $('.export-pdf').on('click', function() {
            // get full url including query string
            var fullUrl = window.location.href;
            if (fullUrl.includes('?')) {
                fullUrl += '&export_pdf=true';
            } else {
                fullUrl += '?export_pdf=true';
            }
            window.location.href = fullUrl;
        })
        $('.form-reset').on('click', function() {
            // get full url without query string
            var fullUrl = window.location.href;
            if (fullUrl.includes('?')) {
                fullUrl = fullUrl.split('?')[0];
            }
            window.location.href = fullUrl;
        })

        $('.search-filter').on('click', function() {
            $('.search-filter-form').slideToggle('slow');
        })
    })
</script>

@php
    $adminRoutes = collect(Route::getRoutes())
        ->filter(fn($route) => str_contains($route->uri(), 'admin')) // Filter only admin routes
        ->map(function ($route) {
            $routeName = $route->getName();
            if (str_contains($routeName, 'show')) {
                return null;
            }

            // Exclude unwanted route names and methods
            if (!$routeName || str_contains($routeName, 'unisharp') || $route->methods()[0] !== 'GET') {
                return null;
            }

            // Exclude authentication and CRUD routes
            $excludedSegments = [
                'login',
                'register',
                'forgot-password',
                'reset-password',
                'create',
                'edit',
                'update',
                'destroy',
                'store',
                'show',
            ];

            if (collect($excludedSegments)->contains(fn($segment) => str_contains($route->uri(), $segment))) {
                return null;
            }

            // Format route title
            $routeTitle = str_replace(['admin.', '.index'], '', $routeName);
            $routeTitle = str_replace(['.', '-'], ' ', $routeTitle);
            $routeTitle = ucwords($routeTitle);

            return [
                'name' => $routeName,
                'title' => $routeTitle,
                'uri' => $route->uri(),
            ];
        })
        ->filter()
        ->values();

@endphp
<script>
    // admin search option
    const inputSelector = "#search_menu";
    const listSelector = "#admin_menu_list";

    const routeList = @json($adminRoutes);


    function filterMenuList() {
        const query = $(inputSelector).val().toLowerCase();
        $(listSelector + " a").each(function() {
            const areaName = $(this).text().toLowerCase();
            $(this).toggle(areaName.includes(query));
        });
    }

    $(inputSelector).on("click", function() {
        $('#searchModal').modal('show')
    });

    $('#search_input').on("input", function() {
        const val = $(this).val().toLowerCase().trim();
        const listContainer = $(listSelector);

        if (!val) {
            listContainer.html("{{ __('No result found') }}");
            return;
        }

        let results = routeList.filter(item => item.title.toLowerCase().includes(val));

        if (results.length === 0) {
            listContainer.removeClass("d-none").html("{{ __('No result found') }}");
            return;
        }

        // Generate search result links
        let resultHtml = results.map(item =>
            `<a href="/${item.uri}" class="list-group-item list-group-item-action">${item.title}</a>`
        ).join('');

        listContainer.removeClass("d-none").html(resultHtml);
    });
    $(document).on("click", function(e) {
        if (
            !$(e.target).closest(inputSelector).length &&
            !$(e.target).closest(listSelector).length
        ) {
            $(listSelector).addClass("d-none");
        }
    });
</script>

@stack('js')
