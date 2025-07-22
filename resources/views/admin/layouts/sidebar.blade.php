<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-favicon demo">
                <img src="{{ asset($setting->admin_favicon ?? $setting->favicon) }}" alt="Logo">
            </span>
            <span class="app-brand-logo demo menu-text fw-bold ms-2">
                <img src="{{ asset($setting->admin_logo ?? $setting->logo) }}" alt="Logo">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <li class="menu-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-home-smile'></i>
                <div class="text-truncate" data-i18n="Basic">{{ __('Dashboard') }}</div>
            </a>
        </li>

        @if (Module::isEnabled('Accounting'))
            @include('accounting::sidebar')
        @endif

        <li class="menu-header">{{ __('Settings') }}</li>

        @if (Module::isEnabled('GlobalSetting'))
            <li
                class="menu-item {{ isRoute(['admin.settings', 'admin.general-setting', 'admin.credential-setting', 'admin.email-settings', 'admin.seo-setting', 'admin.currency*', 'admin.payment', 'admin.system-update*', 'admin.languages*', 'admin.admin*', 'admin.role*', 'admin.cache-clear', 'admin.system.cleanup', 'admin.cron-jobs', 'admin.blog.settings'], 'active') }}">
                <a class="menu-link" href="{{ route('admin.settings') }}">
                    <i class='menu-icon tf-icons bx bx-cog'></i>
                    <div class="text-truncate" data-i18n="Settings">{{ __('Settings') }}</div>
                </a>
            </li>
        @endif

        <li class="mb-5"></li>
    </ul>
</aside>
