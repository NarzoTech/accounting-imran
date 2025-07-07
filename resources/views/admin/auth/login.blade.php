@extends('admin.auth.app')
@section('title')
    <title>{{ __('Login') }}</title>
@endsection
@section('content')
    <div class="col-12 col-lg-5 col-xl-4 col-md-7 m-auto admin_login_form_area">
        <div class="admin_login_form_content">
            <h4>{{ __('Welcome to') }} {{ $setting->app_name }}! 👋</h4>
            <p>{{ __('Please sign-in to your account and start the adventure') }}</p>
            <form id="formAuthentication" action="{{ route('admin.store-login') }}" method="post">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    @if (app()->isLocal() && app()->hasDebugModeEnabled())
                        <input id="email exampleInputEmail" type="email" class="form-control" name="email" tabindex="1"
                            autofocus value="admin@narzotech.com">
                    @else
                        <input id="email exampleInputEmail" type="email" class="form-control" name="email"
                            tabindex="1" autofocus value="{{ old('email') }}">
                    @endif
                </div>
                <div class="mb-5 form-password-toggle">
                    <label class="form-label" for="password">{{ __('Password') }}</label>
                    <div class="input-group input-group-merge">
                        @if (app()->isLocal() && app()->hasDebugModeEnabled())
                            <input id="password exampleInputPassword" type="password" class="form-control" name="password"
                                tabindex="2" value="1234">
                        @else
                            <input id="password exampleInputPassword" type="password" class="form-control" name="password"
                                tabindex="2">
                        @endif
                        <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>
                    </div>
                </div>
                <div class="check_area mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                        <label class="form-check-label" for="remember-me">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                    <a href="{{ route('admin.password.request') }}">
                        <p class="mb-0">{{ __('Forgot Password') }}?</p>
                    </a>
                </div>
                <button class="btn btn-primary d-grid w-100" type="submit"> {{ __('Sign in') }} </button>
            </form>
        </div>
    </div>
@endsection
