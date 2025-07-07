<nav class="navbar navbar-expand-lg main_menu">
    <div class="container container_large">
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
                    <a class="user_icon"
                        href="{{ auth()->check() ? route('website.user.dashboard') : route('login') }}">
                        <span> <img loading="lazy" src="{{ asset('website/assets/images/user_icon_3.png') }}"
                                alt="user" class="img-fluid w-100">
                        </span>
                        {{ auth()->check() ? __('Profile') : __('Login') }}
                    </a>
                </li>
                <li class="manu_btn">
                    <a class="common_btn" href="{{ route('website.user.property.create') }}">{{ __('Add Listing') }}</a>
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
