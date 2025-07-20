@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Edit Customer') }}</title>
@endsection

@section('admin-content')
    <form action="{{ route('admin.customer.update', $customer->id) }}" method="post" id="create_form"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Main Form Area -->
            <div class="col-md-7 col-lg-8">
                <div class="card pb-5">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Edit Customer') }}</h4>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">

                            <!-- Customer Name -->
                            <div class="col-md-12 mb-4">
                                <x-admin.form-input name="name" label="{{ __('Customer Name') }}"
                                    placeholder="{{ __('Enter Business or Customer Name') }}"
                                    value="{{ old('name', $customer->name) }}" required="true" />
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input type="email" name="email" label="{{ __('Email') }}"
                                    placeholder="{{ __('Customer Email') }}" value="{{ old('email', $customer->email) }}" />
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="phone" label="{{ __('Phone') }}"
                                    placeholder="{{ __('Customer Phone') }}"
                                    value="{{ old('phone', $customer->phone) }}" />
                            </div>

                            <!-- First Name -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="first_name" label="{{ __('First Name') }}"
                                    placeholder="{{ __('Enter First Name') }}"
                                    value="{{ old('first_name', $customer->first_name) }}" />
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="last_name" label="{{ __('Last Name') }}"
                                    placeholder="{{ __('Enter Last Name') }}"
                                    value="{{ old('last_name', $customer->last_name) }}" />
                            </div>

                            <!-- Opening Balance -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input type="number" step="0.01" name="opening_balance"
                                    label="{{ __('Opening Balance') }}"
                                    value="{{ old('opening_balance', $customer->opening_balance) }}" />
                            </div>

                            <!-- Balance As Of Date -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input type="date" name="opening_balance_as_of"
                                    label="{{ __('Opening Balance As Of') }}"
                                    value="{{ old('opening_balance_as_of', $customer->opening_balance_as_of) }}" />
                            </div>

                            <!-- Address -->
                            <div class="col-md-12 mb-4">
                                <x-admin.form-textarea name="address" label="{{ __('Address') }}"
                                    value="{{ old('address', $customer->address) }}" />
                            </div>

                            <!-- City -->
                            <div class="col-md-4 mb-4">
                                <x-admin.form-input name="city" label="{{ __('City') }}"
                                    value="{{ old('city', $customer->city) }}" />
                            </div>

                            <!-- State -->
                            <div class="col-md-4 mb-4">
                                <x-admin.form-input name="state" label="{{ __('State') }}"
                                    value="{{ old('state', $customer->state) }}" />
                            </div>

                            <!-- Zip Code -->
                            <div class="col-md-4 mb-4">
                                <x-admin.form-input name="zip_code" label="{{ __('Zip Code') }}"
                                    value="{{ old('zip_code', $customer->zip_code) }}" />
                            </div>

                            <!-- Country -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="country" label="{{ __('Country') }}"
                                    value="{{ old('country', $customer->country) }}" />
                            </div>

                            <!-- Fax -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="fax" label="{{ __('Fax') }}"
                                    value="{{ old('fax', $customer->fax) }}" />
                            </div>

                            <!-- Website -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-input name="website" label="{{ __('Website') }}"
                                    value="{{ old('website', $customer->website) }}" placeholder="https://example.com" />
                            </div>

                            <!-- Customer Type -->
                            <div class="col-md-6 mb-4">
                                <x-admin.form-select name="customer_type" label="{{ __('Customer Type') }}">
                                    <x-admin.select-option value="business" :selected="old('customer_type', $customer->customer_type) === 'business'" text="Business" />
                                    <x-admin.select-option value="person" :selected="old('customer_type', $customer->customer_type) === 'person'" text="Person" />
                                </x-admin.form-select>
                            </div>

                            <!-- Notes -->
                            <div class="col-md-12">
                                <x-admin.form-textarea name="notes" label="{{ __('Notes') }}"
                                    value="{{ old('notes', $customer->notes) }}" />
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
                        <x-admin.form-image-preview name="profile_image" :required="false" :image="asset($customer->profile_image)"
                            :label="__('Profile Image')" />
                    </div>
                </div>

                <!-- Status -->
                <div class="card mt-5">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Status') }}</h4>
                    </div>
                    <div class="card-body">
                        <x-admin.form-switch name="status" label="{{ __('Active') }}" :checked="old('status', $customer->status) == 1" />
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
