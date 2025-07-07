<?php

namespace Modules\Website\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Faq\app\Models\Faq;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\PageBuilder\app\Models\CustomizeablePage;
use Modules\Subscription\app\Models\SubscriptionPlan;

class UtilityController extends Controller
{
    public function pricingPlan()
    {
        $subscriptionPlans = SubscriptionPlan::where('status', 1)->orderBy('serial', 'asc')->get();
        $seo_setting = SeoSetting::where('page_name', 'Pricing Page');
        $faqs = Faq::with('translation')->get();
        return view('website::pages.utilities.pricing-plan', compact('subscriptionPlans', 'seo_setting', 'faqs'));
    }

    public function faqs()
    {
        $faqs = Faq::with('translation')->get();
        $seo_setting = SeoSetting::where('page_name', 'FAQ Page');
        return view('website::pages.utilities.faqs', compact('faqs', 'seo_setting'));
    }

    public function page($slug)
    {
        $page = CustomizeablePage::with('translation')->where('slug', $slug)->first();

        if (!$page) {
            abort(404);
        }

        return view('website::pages.utilities.page', compact('page'));
    }
}
