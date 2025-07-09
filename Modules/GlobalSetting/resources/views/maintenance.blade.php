@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Maintenance') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="section_title">{{ __('Maintenance') }}</h4>
                </div>
                <div class="card-body">
                    <div role="alert" class="alert alert-danger mt-5">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="alert-icon me-0 rounded-circle px-0"><i
                                    class="icon-base bx bx-info-circle icon-18px"></i></span>
                            <div class="col text-wrap ps-3 pe-0">
                                {{ __("If you enable maintenance mode, regular users won't be able to access the website. Please make sure to inform users about the temporary unavailability.") }}
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.update-maintenance-mode') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <x-admin.form-switch name="maintenance_mode" label="{{ __('Maintenance Status') }}"
                                        active_value="1" inactive_value="0" :checked="$setting->maintenance_mode == '1'" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6 col-xxl-3">
                                <div class="form-group">
                                    <x-admin.form-image-preview div_id="maintenance_image_preview"
                                        label_id="maintenance_image_label" input_id="maintenance_image_upload"
                                        :image="$setting->maintenance_image" name="maintenance_image" label="{{ __('Image') }}"
                                        button_label="{{ __('Update Image') }}" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <x-admin.form-input id="maintenance_title" name="maintenance_title"
                                        label="{{ __('Maintenance Mode Title') }}"
                                        placeholder="{{ __('Enter Maintenance Mode Title') }}"
                                        value="{{ $setting->maintenance_title }}" required="true" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <x-admin.form-editor id="maintenance_description" name="maintenance_description"
                                        label="{{ __('Maintenance Mode Description') }}" value="{!! $setting->maintenance_description !!}"
                                        required="true" />
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
