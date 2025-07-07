@extends('website::pages.user.layout.app')
@section('title', __('Pricing Plan'))
@section('user-content')
    <div class="dashboard_content">
        <h2 class="dashboard_title">{{ __('Pricing Plan') }}</h2>
        <div class="dashboard_pricing">
            <div class="row">
                @foreach ($subscriptionPlans as $plan)
                    <div class="col-xxl-4 col-md-6 wow fadeInUp" data-wow-duration="1.5s">
                        <div class="single_pricing">
                            @include('website::components.pricing-plan-details', ['plan' => $plan])
                            <a class="common_btn"
                                href="{{ route('website.checkout') }}?plan={{ $plan->id }}">{{ __('Choose This Pack') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
