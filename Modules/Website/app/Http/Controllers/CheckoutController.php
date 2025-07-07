<?php

namespace Modules\Website\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\Subscription\app\Models\SubscriptionPlan;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkout()
    {
        $pricingPlan = request('plan');
        if (!$pricingPlan) {
            return redirect()->route('website.pricing-plan')->with(['message' => __('Please Choose a Pricing Plan'), 'alert-type' => 'error']);
        }
        $plan = SubscriptionPlan::find($pricingPlan);
        if (!$plan) {
            return redirect()->route('website.pricing-plan')->with(['message' => __('Pricing Plan Not Found'), 'alert-type' => 'error']);
        }

        session()->put('plan', $plan);
        session()->put('package_id', $plan->id);
        session()->put('package_price', $plan->plan_price);

        $payable_amount = $plan->plan_price;

        $user = Auth::guard('web')->user();



        $paymentService = app(\Modules\BasicPayment\app\Services\PaymentMethodService::class);
        $activeGateways = $paymentService->getActiveGatewaysWithDetails();

        $seo_setting = SeoSetting::where('page_name', 'Payment Page');
        return view('website::pages.utilities.payment', compact('plan', 'user', 'payable_amount', 'activeGateways', 'seo_setting'));
    }

    public function failed()
    {
        return view('website::pages.utilities.payment-failed');
    }

    public function success()
    {
        return view('website::pages.utilities.payment-success');
    }
}
