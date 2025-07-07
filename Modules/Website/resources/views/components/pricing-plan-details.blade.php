<h5>{{ $plan->plan_name }}</h5>
<p>{{ $plan->short_description }}</p>
<h2>{{ currency($plan->plan_price) }}</h2>
<ul>
    @if ($plan->number_of_property == -1)
        <li>{{ __('Unlimited Property Submission') }}</li>
    @else
        <li>{{ $plan->number_of_property }} {{ __('Property Submission') }}</li>
    @endif
    @if ($plan->feature == 1)
        <li>{{ $plan->number_of_feature_property }} {{ __('Featured Property') }}</li>
    @else
        <li class="delete">{{ __('Featured Property') }}</li>
    @endif

    <li>{{ $plan->listing_availability == -1 ? __('Unlimited') : $plan->listing_availability }}
        {{ __('Listing Availability') }}</li>
    <li>{{ __('Unlimited Amenity') }}</li>
    <li>{{ __('Unlimited Nearest Place') }}</li>
    <li>{{ __('Unlimited Photo') }}</li>
</ul>
