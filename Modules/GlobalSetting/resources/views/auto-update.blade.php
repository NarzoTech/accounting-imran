@extends('admin.layouts.master')
@section('title')
    <title>{{ __('System Update') . ' - ' . $setting->app_name ?? 'NarzoTech' }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            @if (false)
                <div class="card mb-5">
                    <div class="card-header">
                        <h4 class="section_title">{{ __('OneClick System Updater') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <p class="system_update_message">
                                    {{ __('The system is up-to-date. There are no new versions to update!') }}
                                </p>
                                <div class="d-block">
                                    <button class="btn btn-primary"
                                        id="update-latest-version">{{ __('Update Latest Version') }}</button>
                                </div>
                                <small class="mt-4 d-block">
                                    {{ __("This won't touch or reset your data - it just update the latest version of the system.") }}
                                </small>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4 class="section_title">{{ __('Manual System Updater') }}</h4>
                </div>
                <div class="card-body">
                    @if ($zipLoaded)
                        @if (!$files)
                            <div class="row">
                                <div class="col-12">
                                    <div class="box">
                                        <form action="{{ route('admin.system-update.store') }}" method="POST"
                                            enctype="multipart/form-data" class="dropzone needsclick" id="dropzone">
                                            @csrf
                                            <div class="dz-message needsclick">
                                                Drop files here or click to upload
                                            </div>
                                            <div class="fallback">
                                                <input name="zip_file" type="file" accept="application/zip" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($files)
                            <div class="row">
                                <div class="my-3 border col-12">
                                    <h2 class="pt-2">{{ __('Available Update File Structure') }}</h2>
                                    <hr>
                                    <ul class="mt-3 list-group file-preview-box">
                                        @foreach ($files as $file)
                                            <li class="list-group-item">{{ $file }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-items-center justify-content-between ">
                                        <form action="{{ route('admin.system-update.redirect') }}" method="post">
                                            @csrf
                                            <button class="btn btn-primary">
                                                {{ __('Start Update Process') }}
                                            </button>
                                        </form>

                                        <a href="javascript:;" onclick="deleteData()"
                                            class="btn btn-danger">{{ __('Delete Update File') }}</a>
                                    </div>
                                </div>
                        @endif
                    @else
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center">
                                <h1>{{ __('PHP Extension Zip Not Loaded, Please Enable this first and then try again.') }}
                                </h1>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <x-admin.delete-modal />
@endsection


@push('js')
    <script>
        "use strict";
        $(document).ready(function() {
            $('#update-latest-version').on('click', function() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.system-update.check') }}",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            })
        })

        function deleteData() {
            $("#deleteForm").attr("action", "{{ route('admin.system-update.delete') }}")
            $('#deleteModal').modal('show');
        }

        @if (!$files)
            Dropzone.autoDiscover = false;



            $(document).ready(function() {
                var token = $('input[name="_token"]').val();
                var $list = $('#file-upload-list');

                if ($("#dropzone").length > 0) {
                    var myDropzone = new Dropzone("#dropzone", {
                        url: "{{ route('admin.system-update.store') }}",
                        paramName: "zip_file",
                        maxFiles: 1,
                        chunking: true,
                        forceChunking: true,
                        method: "POST",
                        maxFilesize: {{ $max_upload_size }}, // 400 MB
                        chunkSize: 1 * 1024 * 1024, // 1 MB per chunk
                        parallelChunkUploads: true,
                        acceptedFiles: ".zip",
                        addRemoveLinks: true,
                        timeout: 0, // Optional: Disable timeout for large uploads
                        headers: {
                            "X-CSRF-TOKEN": token
                        },
                        init: function() {
                            const self = this;

                            this.on("addedfile", function(file) {
                                if ($list.length === 0) {
                                    $list = $('<ul id="file-upload-list"></ul>').insertAfter(
                                        '#dropzone');
                                }
                                // Create progress bar container
                                const progressWrapper = $(
                                    '<div class="progress mt-2" style="height: 16px;"></div>'
                                );
                                const progressBar = $(`
                                                <div class="progress-bar bg-success"
                                                    role="progressbar"
                                                    style="width: 0%;"
                                                    aria-valuenow="0"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    0%
                                                </div>
                                            `);
                                progressWrapper.append(progressBar);

                                $list.append(progressWrapper);
                                file._progressBar = progressBar; // Store ref for later updates
                            });

                            this.on("uploadprogress", function(file, progress) {
                                const percent = Math.round(progress);
                                updateProgress(percent);
                            });

                            this.on("success", function(file, response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    $list.append('<li>Uploaded: ' + (response.name || file
                                        .name) + '</li>');
                                    location.reload();
                                } else {
                                    toastr.error(response.message);
                                }
                            });

                            this.on("error", function(file, errorMessage) {
                                toastr.error("File upload failed.");
                            });
                        }

                    });
                }
            });

            function updateProgress(percent) {
                const progressBar = $('.progress-bar');
                progressBar.css('width', percent + '%');
                progressBar.text(percent + '%');
            }
        @endif
    </script>
@endpush
