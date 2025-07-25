@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Crediential Setting') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">

            <div class="section-body">

                <div class="row">
                    <div class="col-md-5 col-xxl-3">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills flex-column" id="credientialTab" role="tablist">
                                    @include('globalsetting::credientials.tabs.navbar')
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 col-xxl-9 small_mt_5">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content p-0" id="myTabContent2">
                                    @include('globalsetting::credientials.sections.google-recaptcha')
                                    @include('globalsetting::credientials.sections.google-tag')
                                    @include('globalsetting::credientials.sections.facebook-pixel')
                                    @include('globalsetting::credientials.sections.social-login')
                                    @include('globalsetting::credientials.sections.tawk-chat')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    <script>
        "use strict";
        $(document).ready(function() {
            $('#copyButton').on('click', function() {
                var copyText = $('#gmail_redirect_url');
                copyText.select();
                document.execCommand('copy');
                toastr.success("{{ __('Copied to clipboard') }}");
            });

            //Tab active setup locally
            activeTabSetupLocally('credientialTab')
        });
    </script>
@endpush
