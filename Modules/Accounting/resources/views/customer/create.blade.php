@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Create Customer') }}</title>
@endsection
@section('admin-content')
    <form action="{{ route('admin.customer.store') }}" method="post" id="create_form" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Main Form Area -->
            <div class="col-md-7 col-lg-8">
                <div class="card  pb-5">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Create Customer') }}</h4>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">

                            <!-- Customer Name -->
                            <div class="col-md-12">
                                <x-admin.form-input name="name" label="{{ __('Customer Name') }}"
                                    placeholder="{{ __('Enter Business or Customer Name') }}" value="{{ old('name') }}"
                                    required="true" />
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <x-admin.form-input type="email" name="email" label="{{ __('Email') }}"
                                    placeholder="{{ __('Customer Email') }}" value="{{ old('email') }}" />
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <x-admin.form-input name="phone" label="{{ __('Phone') }}"
                                    placeholder="{{ __('Customer Phone') }}" value="{{ old('phone') }}" />
                            </div>

                            <!-- First Name -->
                            <div class="col-md-6">
                                <x-admin.form-input name="first_name" label="{{ __('First Name') }}"
                                    placeholder="{{ __('Enter First Name') }}" value="{{ old('first_name') }}" />
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6">
                                <x-admin.form-input name="last_name" label="{{ __('Last Name') }}"
                                    placeholder="{{ __('Enter Last Name') }}" value="{{ old('last_name') }}" />
                            </div>

                            <!-- Opening Balance -->
                            <div class="col-md-6">
                                <x-admin.form-input type="number" step="0.01" name="opening_balance"
                                    label="{{ __('Opening Balance') }}" value="{{ old('opening_balance', 0.0) }}" />
                            </div>

                            <!-- Balance As Of Date -->
                            <div class="col-md-6">
                                <x-admin.form-input type="date" name="opening_balance_as_of"
                                    label="{{ __('Opening Balance As Of') }}"
                                    value="{{ old('opening_balance_as_of') }}" />
                            </div>

                            <!-- Address -->
                            <div class="col-md-12">
                                <x-admin.form-textarea name="address" label="{{ __('Address') }}"
                                    value="{{ old('address') }}" />
                            </div>

                            <!-- City -->
                            <div class="col-md-4">
                                <x-admin.form-input name="city" label="{{ __('City') }}"
                                    value="{{ old('city') }}" />
                            </div>

                            <!-- State -->
                            <div class="col-md-4">
                                <x-admin.form-input name="state" label="{{ __('State') }}"
                                    value="{{ old('state') }}" />
                            </div>

                            <!-- Zip Code -->
                            <div class="col-md-4">
                                <x-admin.form-input name="zip_code" label="{{ __('Zip Code') }}"
                                    value="{{ old('zip_code') }}" />
                            </div>

                            <!-- Country -->
                            <div class="col-md-6">
                                <x-admin.form-input name="country" label="{{ __('Country') }}"
                                    value="{{ old('country') }}" />
                            </div>

                            <!-- Fax -->
                            <div class="col-md-6">
                                <x-admin.form-input name="fax" label="{{ __('Fax') }}"
                                    value="{{ old('fax') }}" />
                            </div>

                            <!-- Website -->
                            <div class="col-md-6">
                                <x-admin.form-input name="website" label="{{ __('Website') }}"
                                    value="{{ old('website') }}" placeholder="https://example.com" />
                            </div>

                            <!-- Customer Type -->
                            <div class="col-md-6">
                                <x-admin.form-select name="customer_type" label="{{ __('Customer Type') }}">
                                    <x-admin.select-option value="business" :selected="old('customer_type') === 'business'" text="Business" />
                                    <x-admin.select-option value="person" :selected="old('customer_type') === 'person'" text="Person" />
                                </x-admin.form-select>
                            </div>

                            <!-- Notes -->
                            <div class="col-md-12">
                                <x-admin.form-textarea name="notes" label="{{ __('Notes') }}"
                                    value="{{ old('notes') }}" />
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-md-5 col-lg-4 small_mt_5">
                <x-admin.action-btn />

                <!-- Image Upload -->
                <div class="card mt-5">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Profile Image') }}</h4>
                    </div>
                    <div class="card-body">
                        <x-admin.form-image-preview name="profile_image" :required="false" />
                    </div>
                </div>

                <!-- Status -->
                <div class="card mt-5">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Status') }}</h4>
                    </div>
                    <div class="card-body">
                        <x-admin.form-switch name="status" label="{{ __('Active') }}" :checked="old('status', 1) == 1" />
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
