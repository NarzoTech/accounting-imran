@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Edit Admin') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">


            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Edit Admin')" />
                                <div>
                                    @adminCan('admin.view')
                                        <x-admin.back-button :href="route('admin.admin.index')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body create_admin_area">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="{{ route('admin.admin.update', $admin->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-admin.form-input id="name" name="name"
                                                            label="{{ __('Name') }}" placeholder="{{ __('Enter Name') }}"
                                                            value="{{ $admin->name }}" required="true" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-admin.form-input type="email" id="email" name="email"
                                                            label="{{ __('Email') }}"
                                                            placeholder="{{ __('Enter Email') }}"
                                                            value="{{ $admin->email }}" required="true" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-admin.form-input type="password" id="password" name="password"
                                                            label="{{ __('Password') }}"
                                                            placeholder="{{ __('Enter Password') }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-admin.form-select id="role" name="role[]"
                                                            label="{{ __('Assign Role') }}" class="select2" required="true"
                                                            multiple>
                                                            <x-admin.select-option disabled value=""
                                                                text="{{ __('Select Role') }}" />
                                                            @foreach ($roles as $role)
                                                                <x-admin.select-option :selected="$admin->hasRole($role->name)"
                                                                    value="{{ $role->name }}"
                                                                    text="{{ $role->name }}" />
                                                            @endforeach
                                                        </x-admin.form-select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <x-admin.form-switch name="status" label="{{ __('status') }}"
                                                            active_value="active" inactive_value="inactive"
                                                            :checked="$admin->status == 'active'" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="col-md-12">
                                                        <x-admin.update-button :text="__('Update')" />
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
