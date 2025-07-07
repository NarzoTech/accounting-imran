@props([
    'fromId' => '#contact-form',
])


<script>
    'use strict';
    $(document).ready(function() {
        $('{{ $fromId }}').on('submit', function(e) {
            e.preventDefault();
            if ($("#g-recaptcha-response").val() === '') {
                e.preventDefault();
                @if ($setting->recaptcha_status == 'active')
                    toastr.error("{{ __('Please complete the recaptcha to submit the form') }}")
                    return;
                @endif
            }
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                beforeSend: function() {
                    // loader
                    $("{{ $fromId }} button[type='submit']").html(
                        '<i class="fas fa-spinner fa-spin"></i> &nbsp; {{ __('Sending Message') }}...'
                    )
                    form.find('button[type="submit"]').prop('disabled', true);

                },
                success: function(response) {
                    if (response.status == 'success') {
                        form[0].reset();
                        toastr.success(response.message);
                        form.find('button[type="submit"]').prop('disabled', false);
                        $("{{ $fromId }} button[type='submit']").html(
                            '{{ __('Send Message') }}'
                        )
                    } else {
                        toastr.error(response.message);
                        form.find('button[type="submit"]').prop('disabled', false);
                        $("{{ $fromId }} button[type='submit']").html(
                            '{{ __('Send Message') }}'
                        )
                    }
                },
                error: function(response) {
                    handleError(response)
                    form.find('button[type="submit"]').prop('disabled', false);
                    $("{{ $fromId }} button[type='submit']").html(
                        '{{ __('Send Message') }}'
                    )
                }
            });
        });
    });
</script>
