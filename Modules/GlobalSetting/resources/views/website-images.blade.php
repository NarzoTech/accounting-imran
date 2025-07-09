@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Website Images') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="section_title">{{ __('Website Images') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.update-logo-favicon') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                        <div class="row">
                            <div class="col-sm-6 col-lg-6 col-xxl-3">
                                <div class="form-group">
                                    <x-admin.form-image-preview :image="$setting->logo" name="logo" label="{{ __('Logo') }}"
                                        button_label="{{ __('Update Image') }}" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6 col-xxl-3">
                                <div class="form-group">
                                    <x-admin.form-image-preview :image="$setting->footer_logo" name="footer_logo"
                                        label="{{ __('Footer Logo') }}" button_label="{{ __('Update Image') }}"
                                        div_id="footer-logo-preview" label_id="footer-logo-label"
                                        input_id="footer-logo-upload" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6 col-xxl-3">
                                <div class="form-group">
                                    <x-admin.form-image-preview div_id="favicon-preview" label_id="favicon-label"
                                        input_id="favicon-upload" :image="$setting->favicon" name="favicon"
                                        label="{{ __('Favicon') }}" button_label="{{ __('Update Image') }}" />
                                </div>
                            </div>

                            @php
                                $img = $setting->auth_image ?? 'website/assets/images/login_bg.jpg';
                            @endphp
                            <div class="col-sm-6 col-lg-6 col-xxl-3">
                                <div class="form-group">
                                    <x-admin.form-image-preview div_id="auth_image-preview" label_id="auth_image-label"
                                        input_id="auth_image-upload" :image="$img" name="auth_image"
                                        label="{{ __('Auth Image') }}" button_label="{{ __('Update Image') }}" />
                                </div>
                            </div>

                            @php
                                $img = $setting->breadcrumb_image ?? 'website/assets/images/login_bg.jpg';
                            @endphp
                            <div class="col-sm-6 col-lg-6 col-xxl-3">
                                <div class="form-group">
                                    <x-admin.form-image-preview div_id="breadcrumb_image-preview"
                                        label_id="breadcrumb_image-label" input_id="breadcrumb_image-upload"
                                        :image="$img" name="breadcrumb_image" label="{{ __('Breadcrumb Image') }}"
                                        button_label="{{ __('Update Image') }}" />
                                </div>
                            </div>
                            <div class="col-12">
                                <x-admin.update-button :text="__('Update')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
