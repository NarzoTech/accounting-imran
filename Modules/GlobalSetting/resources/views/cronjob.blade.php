@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Cronjob') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="section_title">{{ __('Cronjob') }}</h4>
                </div>
                <div class="card-body">
                    <div class="input-group cronjob">
                        <input type="text" id="command" class="form-control"
                            value="* * * * * /usr/local/bin/php path-to-your-project/artisan schedule:run &gt;&gt; /dev/null 2&gt;&amp;1"
                            readonly>
                        <button class="clipboard-btn btn btn-primary px-3" id="copy-command" data-clipboard-action="copy"
                            data-clipboard-target="#command">Copy</button>
                    </div>

                    <div role="alert" class="alert alert-info mt-5">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="alert-icon me-0 rounded-circle px-0"><i
                                    class="icon-base bx bx-info-circle icon-18px"></i></span>
                            <div class="col text-wrap ps-3 pe-0">
                                To run the cronjob, follow the instructions below.
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-5">
                        <h5>Setting Up The Cronjob</h5>
                        <ul class="mt-2">
                            <li>Connect to your server via SSH or any preferred method.
                            </li>
                            <li>Open the crontab file using a text editor (e.g., `crontab -e`).</li>
                            <li>Add the above command to the crontab file and save it.</li>
                            <li>The cronjob will now run at every minute and execute the specified command.</li>
                        </ul>

                        <p class="border-top pt-4 mt-2 mb-0">
                            You can learn more about cronjob from the Laravel <a
                                href="https://laravel.com/docs/12.x/scheduling" target="_blank"
                                class="hover:underline text-primary-500" rel="noreferrer noopener">documentation</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {

            var clipboard = new ClipboardJS('#copy-command');

            clipboard.on('success', function(e) {
                toastr.success('{{ __('Command copied to clipboard!') }}', '', options);
                e.clearSelection();
                $('#copy-command').html('{{ __('Copied') }}').attr('disabled', true);
            });

            clipboard.on('error', function(e) {
                toastr.error("{{ __('Failed to copy!') }}", '', options);
            });
        });
    </script>
@endpush
