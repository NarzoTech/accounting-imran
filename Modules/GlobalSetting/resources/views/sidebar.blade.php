<li class="menu-header">{{ __('Settings') }}</li>
<li class="{{ Route::is('admin.general-setting') ? 'active' : '' }}"><a class="nav-link"
        href="{{ route('admin.general-setting') }}"><i class="fas fa-cog"></i>
        <span>{{ __('General Settings') }}</span></a></li>

<li class="{{ Route::is('admin.credential-setting') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.credential-setting') }}"><i class="fas fa-key"></i>
        <span>{{ __('Credential Settings') }}</span>
    </a>
</li>

<li class="{{ Route::is('admin.email-settings') || Route::is('admin.edit-email-template') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.email-settings') }}"><i class="fas fa-envelope"></i>
        <span>{{ __('Email Configuration') }}</span>
    </a>
</li>

@if (Module::isEnabled('Language') && checkAdminHasPermission('language.view'))
    @include('language::sidebar')
@endif

<li class="{{ Route::is('admin.seo-setting') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.seo-setting') }}"><i class="fas fa-search"></i>
        <span>{{ __('SEO Setup') }}</span>
    </a>
</li>
@if (Module::isEnabled('Sitemap') && checkAdminHasPermission('sitemap.management'))
    @include('sitemap::sidebar')
@endif
<li class="menu-header">{{ __('Extra Settings') }}</li>
<li class="nav-item dropdown {{ isRoute('admin.custom-code', 'active') }}">
    <a href="javascript:;" class="nav-link has-dropdown">
        <i class="fas fa-code"></i><span>{{ __('Custom Code') }}</span>
    </a>
    <ul class="dropdown-menu">
        <li class="{{ request()->routeIs('admin.custom-code') && request('type') == 'css' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.custom-code', 'css') }}">
                {{ __('CSS') }}
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.custom-code') && request('type') == 'js' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.custom-code', 'js') }}">
                {{ __('JS') }}
            </a>
        </li>
    </ul>
</li>

<li class="{{ Route::is('admin.cache-clear') ? 'active' : '' }}"><a class="nav-link"
        href="{{ route('admin.cache-clear') }}"><i class="fas fa-sync"></i>
        <span>{{ __('Clear cache') }}</span>
    </a></li>

<li class="{{ Route::is('admin.system.cleanup') ? 'active' : '' }}"><a class="nav-link"
        href="{{ route('admin.system.cleanup') }}"><i class="fas fa-database"></i>
        <span>{{ __('Database Clear') }}</span></a></li>

<li class="{{ Route::is('admin.system-update.index') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.system-update.index') }}"><i class="fas fa-arrow-circle-up"></i>
        <span>{{ __('System Update') }}</span>
    </a>
</li>
