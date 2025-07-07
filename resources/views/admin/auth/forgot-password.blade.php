@extends('admin.auth.app')
@section('title')
    <title>{{ __('Forgot Password') }}</title>
@endsection
@section('content')
    <div class="col-12 col-lg-5 col-xl-4 col-md-7 m-auto admin_login_form_area">
        <div class="admin_login_form_content">
            <h4>{{ __('Forgot Password') }}</h4>
            <form action="{{ route('admin.forget-password') }}" method="POST">
                @csrf

                <div class="mt-4 mb-5">
                    <label for="email">{{ __('Email') }}</label>
                    <input id="email exampleInputEmail" type="email" class="form-control" name="email" tabindex="1"
                        autofocus placeholder="{{ old('email') }}">
                </div>
                <button id="adminLoginBtn" type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    {{ __('Send Reset Link') }}
                </button>
                <a class="back_btn" href="{{ route('admin.login') }}">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Go to login page') }}
                </a>
            </form>
        </div>
    </div>
@endsection
