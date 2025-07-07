@props(['route' => ''])
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4 class="section_title">{{ __('Action') }}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary" id="save_btn" name="button" form="create_form"
                    value="save">
                    <i class="fas fa-save"></i>
                    {{ __('Save') }}
                </button>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-outline-dark mt-3" id="save_exit_btn" value="save_exit"
                    name="button">
                    <i class="fas fa-sign-out-alt"></i>
                    {{ __('Save & Exit') }}
                </button>
            </div>
            <div class="col-12">
                <button type="button" class="btn btn-outline-danger mt-3" id="cancel_btn">
                    <i class="fas fa-window-close"></i>
                    {{ __('Cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            $('#cancel_btn').on('click', function() {
                const route = "{{ $route }}";
                if (route) {
                    window.location.href = "{{ route('admin.' . $route) }}";
                } else {
                    // one step back
                    window.history.back();
                }
            });
        });
    </script>
@endpush
