<nav class="navbar navbar-expand-lg main_menu main_menu_2">
    <div class="container container_extra_large">
        @include('website::layouts.partials.logo')
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars bar_icon"></i>
            <i class="far fa-times close_icon"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            @include('website::layouts.partials.nav-item')
            <ul class="menu_right d-flex align-items-center">
                <li>
                    <a class="user_icon user_call" href="callto:1213456789">
                        <i class="fas fa-phone-alt"></i>
                        (088) 777 - 5643
                    </a>
                </li>
                <li class="manu_btn main_manu_btn">
                    <a class="common_btn"
                        href="{{ auth()->check() ? route('website.user.dashboard') : route('login') }}">
                        <i class="far fa-user"></i>
                        {{ auth()->check() ? __('Profile') : __('Login') }}
                    </a>
                </li>
                <li>
                    <div class="toggol_bar" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                        aria-controls="offcanvasRight">
                        <span class="toggol_bar_1"></span>
                        <span class="toggol_bar_2"></span>
                        <span class="toggol_bar_1"></span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
