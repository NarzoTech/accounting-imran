@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Admin Logo & Favicon') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="section_title">{{ __('Admin Logo & Favicon') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.update-admin-logo-favicon') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-sm-4 col-lg-4 col-xxl-4">
                                <div class="form-group">
                                    <x-admin.form-image-preview :image="$setting->admin_logo ?? ''" name="admin_logo"
                                        label="{{ __('Logo') }}" button_label="{{ __('Update Image') }}"
                                        div_id="admin-logo-preview" label_id="admin-logo-label"
                                        input_id="admin-logo-upload" />
                                </div>
                            </div>
                            <div class="col-sm-4 col-lg-4 col-xxl-4">
                                <div class="form-group">
                                    <x-admin.form-image-preview div_id="admin-favicon-preview"
                                        label_id="admin-favicon-label" input_id="admin-favicon-upload" :image="$setting->admin_favicon ?? ''"
                                        name="admin_favicon" label="{{ __('Favicon') }}"
                                        button_label="{{ __('Update Image') }}" />
                                </div>
                            </div>

                            @php
                                $img =
                                    $setting->admin_login_image ??
                                    'backend/assets/img/illustrations/boy-with-rocket-light.png';
                            @endphp
                            <div class="col-sm-4 col-lg-4 col-xxl-4">
                                <div class="form-group">
                                    <x-admin.form-image-preview div_id="admin-login-preview" label_id="admin-login-label"
                                        input_id="admin-login-upload" :image="$img" name="admin_login_image"
                                        label="{{ __('Login Page Image') }}" button_label="{{ __('Login Page Image') }}" />
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
