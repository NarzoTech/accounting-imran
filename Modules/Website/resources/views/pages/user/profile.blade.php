@extends('website::pages.user.layout.app')
@section('title', __('Profile'))
@section('user-content')
    <div class="dashboard_content">
        <h2 class="dashboard_title">{{ __('Personal Information') }}</h2>
        <div class="dashboard_profile">
            <div class="dashboard_profile_info">
                <div class="row">
                    <div class="col-xxl-4 col-md-5 col-lg-6 col-xl-5 wow fadeInLeft" data-wow-duration="1.5s">
                        <div class="dashboard_profile_img">
                            <img loading="lazy" src="{{ asset(auth()->user()->image) }}" alt="agent"
                                class="img-fluid w-100">
                        </div>
                    </div>
                    <div class="col-xxl-8 col-md-7 col-lg-6 col-xl-7 wow fadeInRight" data-wow-duration="1.5s">
                        <div class="dashboard_profile_text">
                            <h3>{{ auth()->user()->name }}</h3>
                            <ul class="list">
                                <li><span>{{ __('Email:') }}</span>{{ auth()->user()->email }}</li>
                                <li><span>{{ __('Phone:') }}</span> {{ auth()->user()->phone }}</li>

                                <li><span>{{ __('Address:') }}</span> {{ auth()->user()->address }}</li>
                            </ul>
                            <ul class="icon d-flex flex-wrap">
                                <li><a href="{{ auth()->user()->facebook }}"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="{{ auth()->user()->linkedin }}"><i class="fab fa-linkedin-in"></i></a></li>
                                <li><a href="{{ auth()->user()->twitter }}"><i class="fab fa-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard_profile_edit wow fadeInUp" data-wow-duration="1.5s">
                <h3>{{ __('update Information') }}</h3>
                <form action="{{ route('website.user.update.profile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('name') }}</label>
                            <input type="text" placeholder="Name" name="name" value="{{ auth()->user()->name }}">
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('email') }}</label>
                            <input type="email" placeholder="Email" name="email" disabled
                                value="{{ auth()->user()->email }}">
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('Phone') }}</label>
                            <input type="text" placeholder="Phone" name="phone" value="{{ auth()->user()->phone }}">
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('Website') }}</label>
                            <input type="text" placeholder="Website" name="website"
                                value="{{ auth()->user()->website }}">
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('Designation') }}</label>
                            <input type="text" placeholder="{{ __('Designation') }}" name="designation"
                                value="{{ auth()->user()->designation }}">
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('Whatsapp Number') }}</label>
                            <input type="text" placeholder="{{ __('Whatsapp Number') }}" name="whatsapp_number"
                                value="{{ auth()->user()->whatsapp_number }}">
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('Profession Start') }}</label>
                            <input type="text" placeholder="{{ __('Profession Start') }}" name="profession_start"
                                value="{{ auth()->user()->profession_start }}">
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('Address') }}</label>
                            <input type="text" placeholder="Address" name="address"
                                value="{{ auth()->user()->address }}">
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('profile photo') }}</label>
                            <input type="file" name="image" accept="image/*">
                        </div>

                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('Facebook') }}</label>
                            <input type="text" placeholder="Facebook" name="facebook"
                                value="{{ auth()->user()->facebook }}">
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('Linkedin') }}</label>
                            <input type="text" placeholder="Linkedin" name="linkedin"
                                value="{{ auth()->user()->linkedin }}">
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('Twitter') }}</label>
                            <input type="text" placeholder="Twitter" name="twitter"
                                value="{{ auth()->user()->twitter }}">
                        </div>
                        <div class="col-12">
                            <label>{{ __('About Us') }}</label>
                            <textarea name="about">{{ auth()->user()->about }}</textarea>
                        </div>
                        <div class="col-12">
                            <button class="common_btn" type="submit">{{ __('update Information') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="dashboard_profile_edit wow fadeInUp" data-wow-duration="1.5s">
                <h3>{{ __('Update Password') }}</h3>
                <form action="{{ route('website.user.update.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('Current Password') }}</label>
                            <input type="password" placeholder="{{ __('Current Password') }}" name="current_password">
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <label>{{ __('New Password') }}</label>
                            <input type="password" placeholder="{{ __('New Password') }}" name="password">
                        </div>
                        <div class="col-xl-4">
                            <label>{{ __('Confirm Password') }}</label>
                            <input type="password" placeholder="{{ __('Confirm Password') }}"
                                name="password_confirmation">
                        </div>
                        <div class="col-12">
                            <button class="common_btn mt-0" type="submit">{{ __('update Password') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </section>
    {{-- <!--=========================
        DASHBOARD INFO END
    ==========================--> --}}
@endsection
