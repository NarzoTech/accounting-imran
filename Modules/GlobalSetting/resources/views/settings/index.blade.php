@extends('admin.layouts.master') @section('title')
    <title>{{ __('General Setting') }}</title>
    @endsection @section('admin-content')
    <div class="main-content">
        <section class="section">

            <div class="section-body general_setting_area">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="section_title">{{ __('General Settings') }}</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.update-general-setting') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="app_name" name="app_name"
                                                    label="{{ __('Company Name') }}" value="{{ $setting->app_name }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="company_address" name="company_address"
                                                    label="{{ __('Company Address') }}"
                                                    value="{{ $setting->company_address }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="company_email" name="company_email"
                                                    label="{{ __('Company Email') }}"
                                                    value="{{ $setting->company_email }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="company_phone" name="company_phone"
                                                    label="{{ __('Company Email') }}"
                                                    value="{{ $setting->company_phone }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="invoice_start" name="invoice_start"
                                                    label="{{ __('Invoice Start') }}"
                                                    value="{{ $setting->invoice_start }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="invoice_prefix" name="invoice_prefix"
                                                    label="{{ __('Invoice Prefix') }}"
                                                    value="{{ $setting->invoice_prefix }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <x-admin.form-textarea id="copyright_text" name="copyright_text"
                                                label="{{ __('Copyright Text') }}"
                                                placeholder="{{ __('Enter Copyright Text') }}"
                                                value="{{ $setting->copyright_text }}" maxlength="1000" />
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-lg-6 col-xxl-3">
                                        <div class="form-group">
                                            <x-admin.form-image-preview div_id="default_avatar_preview"
                                                label_id="default_avatar_label" input_id="default_avatar_upload"
                                                :image="$setting->default_avatar" name="default_avatar" label="{{ __('Default Avatar') }}"
                                                button_label="{{ __('Update Image') }}" />
                                        </div>
                                    </div>

                                    <x-admin.update-button :text="__('Update')" />

                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
