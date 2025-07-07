@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Create User') }}</title>
@endsection
@section('admin-content')
    <form action="{{ route('admin.admin.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Create User') }}</h4>
                    </div>
                    <div class="card-body pb-1">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input id="name" name="name" label="{{ __('Name') }}"
                                        placeholder="{{ __('Enter Name') }}" value="{{ old('name') }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="email" id="email" name="email"
                                        label="{{ __('Email') }}" placeholder="{{ __('Enter Email') }}"
                                        value="{{ old('email') }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="password" id="password" name="password"
                                        label="{{ __('Password') }}" placeholder="{{ __('Enter Password') }}"
                                        value="{{ old('password') }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-select id="role" name="role[]" label="{{ __('Assign Role') }}"
                                        class="select2" multiple>
                                        <x-admin.select-option value="" disabled text="{{ __('Select Role') }}" />
                                        @foreach ($roles as $role)
                                            <x-admin.select-option value="{{ $role->name }}"
                                                text="{{ $role->name }}" />
                                        @endforeach
                                    </x-admin.form-select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <x-admin.action-btn />
                <div class="card mt-5">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="section_title">{{ __('Status') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <x-admin.form-switch name="status" label="{{ __('status') }}" active_value="active"
                                    inactive_value="inactive" :checked="old('status') == 'active'" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
