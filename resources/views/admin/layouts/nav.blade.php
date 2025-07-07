<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0">
        <a class="nav_toggler_btn nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="bx bx-menu bx-md"></i>
        </a>
    </div>
    <div class="navbar-nav-right d-flex flex-wrap align-items-center" id="navbar-collapse">
        <!-- Search start-->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center navbar_search position-relative">
                <i class="bx bx-search bx-md"></i>
                <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2"
                    placeholder="{{ __('Search') }}..." aria-label="Search..." id="search_menu">
            </div>
        </div>
        <!-- Search end-->

        <ul class="navbar-nav flex-wrap flex-row align-items-center ms-auto">


            <!-- User -->
            <li class="navbar-dropdown dropdown-user dropdown ms-3">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset($header_admin->image_url) }}" alt class="w-px-40 h-auto rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.edit-profile') }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset($header_admin->image_url) }}" alt
                                            class="w-px-40 h-auto rounded-circle">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $header_admin->name }}</h6>
                                    <small class="text-muted">{{ $header_admin->getRoleNames()->first() }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ route('admin.edit-profile') }}">
                            <i class="bx bx-user bx-md me-3"></i><span>{{ __('My Profile') }}</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ route('admin.settings') }}">
                            <i class="bx bx-cog bx-md me-3"></i><span>{{ __('Settings') }}</span>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="javascript:void(0);"
                            onclick="event.preventDefault();
                                document.getElementById('admin-logout-form').submit();">
                            <i class="bx bx-power-off bx-md me-3"></i><span>{{ __('Log Out') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>


<div tabindex="-1" role="dialog" id="searchModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="input-group input-group-merge">
                    <span class="input-group-text" id="basic-addon-search31"><i
                            class="icon-base bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="{{ __('Search') }}..." id="search_input"
                        autocomplete="off">
                </div>
                <div id="admin_menu_list" class="d-flex flex-column position-absolute rounded-2">
                    {{ __('No result found') }}
                </div>
            </div>
        </div>
    </div>
</div>
