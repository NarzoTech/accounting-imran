@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Profile') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-6">
                <div class="user-profile-header-banner">
                    <img src="{{ asset('backend/img/profile-banner.png') }}" alt="Banner image" class="rounded-top" />
                </div>
                <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-8">
                    <div class="flex-shrink-0 mt-1 mx-sm-0 mx-auto">
                        <img src="{{ !empty($admin->image) ? asset($admin->image) : asset($setting->default_avatar) }}"
                            alt="user image" class="d-block h-auto ms-0 ms-sm-6 rounded-3 user-profile-img" />
                    </div>
                    <div class="flex-grow-1 mt-3 mt-lg-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4 class="mb-2 mt-lg-7">{{ $admin->name }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 mt-4">

                                    <li class="list-inline-item"><i
                                            class="icon-base bx bx-calendar me-2 align-top"></i><span class="fw-medium">
                                            {{ __('Joined') }} {{ $admin->created_at->format('F Y') }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->


    {{-- check user has super admin role --}}
    @php
        $roles = $admin->getRoleNames()->toArray();
        $hasSuperAdminRole = in_array('Super Admin', $roles);
    @endphp

    <!-- Navbar pills -->
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-sm-0 gap-2">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:;" data-bs-toggle="tab" data-bs-target="#profile"><i
                                class="icon-base bx bx-user icon-sm me-1_5"></i> {{ __('Profile') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:;" data-bs-toggle="tab" data-bs-target="#password"><i
                                class="icon-base bx bx-key icon-sm me-1_5"></i> {{ __('Change Password') }}</a>
                    </li>
                    @if (!$hasSuperAdminRole)
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:;" data-bs-toggle="tab" data-bs-target="#account_delete"><i
                                    class="icon-base bx bx-lock-alt icon-sm me-1_5"></i> {{ __('Security') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <!--/ Navbar pills -->

    <!-- User Profile Content -->
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5">
            <!-- About User -->
            <div class="card mb-6">
                <div class="card-body">
                    <small class="card-text text-uppercase text-body-secondary small">{{ __('About') }}</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-user"></i><span
                                class="fw-medium mx-2">{{ __('Name') }}:</span> <span>{{ $admin->name }}</span></li>
                        <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-check"></i><span
                                class="fw-medium mx-2">{{ __('Status') }}:</span>
                            <span>{{ ucfirst($admin->status) }}</span>
                        </li>

                        <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-crown"></i><span
                                class="fw-medium mx-2">{{ __('Role') }}:</span> <span>
                                @foreach ($admin->getRoleNames() as $role)
                                    {{ $role }} {{ $loop->last ? '' : ',' }}
                                @endforeach
                            </span></li>
                    </ul>
                    <small class="card-text text-uppercase text-body-secondary small">{{ __('Contacts') }}</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-phone"></i><span
                                class="fw-medium mx-2">{{ __('Contact') }}:</span> <span>{{ $admin->phone }}</span></li>

                        <li class="d-flex align-items-center mb-4"><i class="icon-base bx bx-envelope"></i><span
                                class="fw-medium mx-2">{{ __('Email') }}:</span> <span>{{ $admin->email }}</span></li>
                    </ul>
                </div>
            </div>
            <!--/ About User -->

        </div>
        <div class="col-xl-8 col-lg-7 col-md-7">


            <div class="tab-content p-0">
                {{-- profile --}}
                <div class="tab-pane fade active show" id="profile" role="tabpanel">
                    <div class="card card-action mb-6">
                        <div class="card-header align-items-center">
                            <h5 class="card-action-title mb-0"><i
                                    class="icon-base bx bx-user icon-lg text-body me-4"></i>{{ __('Edit Profile') }}
                            </h5>
                        </div>
                        <div class="card-body pt-3">
                            <form @adminCan('admin.profile.update') action="{{ route('admin.profile-update') }}"
                                @endadminCan enctype="multipart/form-data" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="form-group col-12">
                                        <x-admin.form-image-preview :image="!empty($admin->image) ? $admin->image : $setting->default_avatar" label="{{ __('Profile Image') }}"
                                            button_label="{{ __('Change Image') }}" />
                                    </div>

                                    <div class="form-group col-12">
                                        <x-admin.form-input id="name" name="name" label="{{ __('Name') }}"
                                            value="{{ $admin->name }}" required="true" />
                                    </div>

                                    <div class="form-group col-12">
                                        <x-admin.form-input type="email" id="email" name="email"
                                            label="{{ __('Email') }}" value="{{ $admin->email }}" required="true" />
                                    </div>

                                    <div class="form-group col-12">
                                        <x-admin.form-input type="phone" id="phone" name="phone"
                                            label="{{ __('Phone') }}" value="{{ $admin->phone }}" />
                                    </div>
                                </div>
                                @adminCan('admin.profile.update')
                                    <div class="row">
                                        <div class="col-12">
                                            <x-admin.update-button :text="__('Update')" />
                                        </div>
                                    </div>
                                @endadminCan
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="password" role="tabpanel">
                    <div class="card ">
                        <div class="card-body">
                            <form @adminCan('admin.profile.update') action="{{ route('admin.update-password') }}"
                                @endadminCan enctype="multipart/form-data" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="form-group col-12">
                                        <x-admin.form-input type="password" id="current_password" name="current_password"
                                            label="{{ __('Current Password') }}" required="true" />
                                    </div>

                                    <div class="form-group col-12">
                                        <x-admin.form-input type="password" id="password" name="password"
                                            label="{{ __('New Password') }}" required="true" />
                                    </div>

                                    <div class="form-group col-12">
                                        <x-admin.form-input type="password" id="password_confirmation"
                                            name="password_confirmation" label="{{ __('Confirm Password') }}"
                                            required="true" />
                                    </div>

                                </div>
                                @adminCan('admin.profile.update')
                                    <div class="row">
                                        <div class="col-12">
                                            <x-admin.update-button id="update-btn-2" :text="__('Update')" />
                                        </div>
                                    </div>
                                @endadminCan
                            </form>
                        </div>
                    </div>
                </div>

                @if (!$hasSuperAdminRole)
                    <div class="tab-pane fade" id="account_delete" role="tabpanel">
                        <div class="card">
                            <h5 class="card-header">{{ __('Delete Account') }}</h5>
                            <div class="card-body">
                                <div class="mb-6 col-12 mb-0">
                                    <div class="alert alert-danger">

                                        <h5 class="alert-heading mb-1"><i class="fas fa-exclamation-triangle"></i>
                                            {{ __('Are you sure you want to delete your account') }}?</h5>
                                        <p class="mb-0">
                                            {{ __('Once you delete your account, there is no going back. Please be certain') }}.
                                        </p>
                                    </div>
                                </div>
                                <form id="formAccountDeactivation" action="{{ route('admin.profile-delete') }}"
                                    class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="form-check my-8 ms-2">
                                        <input class="form-check-input" type="checkbox" name="accountActivation"
                                            id="accountActivation" value="1">
                                        <label class="form-check-label" for="accountActivation">
                                            {{ __('I confirm my account Deletion') }}
                                        </label>
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <div class="form-check my-8">
                                        <input class="form-control" type="password" name="password" id="password">
                                        <label class="form-check-label" for="password">
                                            {{ __('Password') }}
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-danger deactivate-account"
                                        disabled>{{ __('Delete Account') }}</button>
                                    <input type="hidden">
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script>
        'use strict';

        $(document).ready(function() {
            $('#accountActivation').on('change', function() {
                if (this.checked) {
                    $('.deactivate-account').prop('disabled', false);
                } else {
                    $('.deactivate-account').prop('disabled', true);
                }
            });
        });
    </script>
@endpush
