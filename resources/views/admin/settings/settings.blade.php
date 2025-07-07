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
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.email-settings') }}">
                                    <h5>{{ __('Email Configuration') }}</h5>
                                    <span>{{ __('View and update your email settings') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-key"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.credential-setting') }}">
                                    <h5>{{ __('Credential Settings') }}</h5>
                                    <span>{{ __('View and update your credential settings') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.currency.index') }}">
                                    <h5>{{ __('Currency') }}</h5>
                                    <span>{{ __('View and update your currency') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-money-check-alt"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.payment') }}">
                                    <h5>{{ __('Payment Setting') }}</h5>
                                    <span>{{ __('View and update your payment settings') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.pagination') }}">
                                    <h5>{{ __('Pagination Settings') }}</h5>
                                    <span>{{ __('Manage Website Pagination') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.system-update.index') }}">
                                    <h5>{{ __('System Update') }}</h5>
                                    <span>{{ __('Update your system') }}</span>
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
                        {{ __('Language Settings') }}
                    </h4>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.languages.index') }}">
                                    <h5>{{ __('Manage Language') }}</h5>
                                    <span>{{ __('Create and update languages') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-language"></i>
                                </div>
                                <a class="settings_item_text d-block"
                                    href="{{ route('admin.languages.edit-static-languages', getSessionLanguage()) }}">
                                    <h5>{{ __('Manage Translation') }}</h5>
                                    <span>{{ __('View and update your languages') }}</span>
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
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.role.index') }}">
                                    <h5>{{ __('Role & Permissions') }}</h5>
                                    <span>{{ __('Create and update roles') }}</span>
                                </a>
                            </div>
                        </div>
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
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-broom"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.system.cleanup') }}">
                                    <h5>{{ __('Cleanup System') }}</h5>
                                    <span>{{ __('Cleanup your unused data in database') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-clock"></i> <i class="fas fa-cog ml-1"></i>

                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.cron-jobs') }}">
                                    <h5>{{ __('Cronjob') }}</h5>
                                    <span>{{ __('Cronjob allow you to automate certain commands or scripts on your site.') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.maintenance') }}">
                                    <h5>{{ __('Manage Maintenance') }}</h5>
                                    <span>{{ __('Manage your maintenance mode') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-cookie"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.cookie') }}">
                                    <h5>{{ __('Website Cookies') }}</h5>
                                    <span>{{ __('Manage your website cookies') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-calendar-alt"></i> <i class="fas fa-clock"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.admin-date-time') }}">
                                    <h5>{{ __('Date & Time Settings') }}</h5>
                                    <span>{{ __('Manage your date and time settings') }}</span>
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
                        <div class="col-sm-6 col-lg-4">
                            <div class="settings_item d-flex flex-wrap align-items-center gap-3">
                                <div class="settings_item_icon">
                                    <i class="fas fa-sliders-h"></i>
                                </div>
                                <a class="settings_item_text d-block" href="{{ route('admin.website-setting') }}">
                                    <h5>{{ __('Website Settings') }}</h5>
                                    <span>{{ __('Manage your website images') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
