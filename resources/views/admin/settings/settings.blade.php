@extends('admin.layouts.master')
@section('title')
    <title>{{ __('Settings') }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="section_title">
                        {{ __('Common Settings') }}
                    </h4>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.general-setting') }}">
                                    <h5>{{ __('General Setting') }}</h5>
                                    <span>{{ __('View and update your general settings') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.admin-setting') }}">
                                    <h5>{{ __('Admin Settings') }}</h5>
                                    <span>{{ __('Manage your admin settings') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-header">
                    <h4 class="section_title">
                        {{ __('System Settings') }}
                    </h4>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-recycle"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.cache-clear') }}">
                                    <h5>{{ __('Cache Management') }}</h5>
                                    <span>{{ __('Clear cache to make your site up to date.') }}</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
