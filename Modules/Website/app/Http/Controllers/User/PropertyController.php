<?php

namespace Modules\Website\app\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Location\app\Models\City;
use Modules\Location\app\Models\Country;
use Modules\Location\app\Models\State;
use Modules\Order\app\Models\Order;
use Modules\Property\app\Http\Requests\PropertyRequest;
use Modules\Property\app\Models\Property;
use Modules\Property\app\Services\AminityService;
use Modules\Property\app\Services\PropertyPurposeService;
use Modules\Property\app\Services\PropertyService;
use Modules\Property\app\Services\PropertyTypeService;
use Modules\Subscription\app\Models\SubscriptionPlan;


class PropertyController extends Controller
{
    public function __construct(private AminityService $aminityService, private PropertyTypeService $propertyTypeService, private PropertyPurposeService $propertyPurposeService, private PropertyService $propertyService)
    {
        $this->middleware('auth:web');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = $this->propertyService->list()->where('user_id', Auth::guard('web')->user()->id)->paginate(20);

        return view('website::pages.user.property.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::guard('web')->user();
        $order = Order::where(['user_id' => $user->id, 'status' => 1])->first();
        if (!$order) {
            $notification = array(
                'message' => __('Please Purchase a Package First'),
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }
        $isExpired = false;
        if ($order->expired_day != -1) {
            if (date('Y-m-d') > $order->expired_date) {
                $isExpired = true;
            }
        }

        if ($isExpired == true) {
            $notification = array(
                'message' => __('Your package has expired'),
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }
        $expired_date = $order->expired_date ?? -1;

        $countries = Country::where('status', 1)->get();
        $amenities = $this->aminityService->forDropDown();
        $propertyTypes = $this->propertyTypeService->forDropDown();
        $package = SubscriptionPlan::find($order->subscription_plan_id);
        $purposes = $this->propertyPurposeService->forDropDown();
        if (!$package) {
            return redirect()->back()->with([
                'message' => __('Please Purchase a Package First'),
                'alert-type' => 'error'
            ]);
        }

        $existListings = Property::where('user_id', $user->id)->count();
        $existingFeaturedListing = Property::where('user_id', $user->id)
            ->where('is_featured', 1)
            ->count();
        if ($package->number_of_property == -1 || $existListings < $package->number_of_property) {
            return view('website::pages.user.property.create', compact(
                'countries',
                'amenities',
                'package',
                'existingFeaturedListing',
                'expired_date',
                'propertyTypes',
                'purposes'
            ));
        }

        return redirect()->route('website.user.dashboard')->with([
            'message' => __('Maximum Listing Already Exist'),
            'alert-type' => 'error'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PropertyRequest $request): RedirectResponse
    {
        $requestData = $request->validated();

        $order = Order::where(['user_id' => auth()->user()->id, 'status' => 1])->first();

        if (!$order) {
            return redirect()->back()->with([
                'message' => __('Please Purchase a Package First'),
                'alert-type' => 'error'
            ]);
        }

        $package = SubscriptionPlan::find($order->subscription_plan_id);

        // number_of_feature_property
        if ($package->feature == 1 && $package->number_of_feature_property != -1) {

            // check total feature listing
            $totalFeatureListing = Property::where(['user_id' => auth()->user()->id, 'is_featured' => 1])->count();

            if ($totalFeatureListing >= $package->number_of_feature_property) {
                return redirect()->back()->with([
                    'message' => __('You have reached the maximum number of featured listings'),
                    'alert-type' => 'error'
                ]);
            }

            $requestData['is_featured'] = 0;
        }

        $requestData['user_id'] = auth()->user()->id;
        $requestData['status'] = 0;
        $requestData['verified'] = 0;

        DB::beginTransaction();
        try {

            $data = $this->propertyService->store($request, $requestData);
            DB::commit();
            return redirect()->route('website.user.property.index')->with([
                'message' => __('Saved Successfully'),
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->info($e->getMessage());
            return redirect()->back()->with([
                'message' => "Property Creation Failed",
                'alert-type' => 'error'
            ]);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('website::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $property = Property::where('user_id', auth()->user()->id)->where('id', $id)->first();


        if (!$property) {
            return redirect()->route('website.user.property.index')->with([
                'message' => __('Property Not Found'),
                'alert-type' => 'error'
            ]);
        }

        $property_amenities = $property->amenities->pluck('id')->toArray();

        $countries = Country::where('status', 1)->get();
        $city = City::where('id', $property->city_id)->first();

        $selectedCountry = $city?->state->country;
        $selectedState = $city?->state?->cities;


        $amenities = $this->aminityService->forDropDown();
        $propertyTypes = $this->propertyTypeService->forDropDown();
        $purposes = $this->propertyPurposeService->forDropDown();

        return view('website::pages.user.property.edit', compact('property', 'property_amenities', 'selectedCountry', 'amenities', 'propertyTypes', 'purposes', 'city', 'selectedState', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PropertyRequest $request, $id): RedirectResponse
    {
        $requestData = $request->validated();
        $requestData['user_id'] = auth()->user()->id;
        $requestData['status'] = 0;
        $requestData['verified'] = 0;

        // number_of_feature_property
        $order = Order::where(['user_id' => auth()->user()->id, 'status' => 1])->first();

        if (!$order) {
            return redirect()->back()->with([
                'message' => __('Please Purchase a Package First'),
                'alert-type' => 'error'
            ]);
        }


        $package = SubscriptionPlan::find($order->subscription_plan_id);

        // number_of_feature_property
        if ($request->is_featured == 1 && $package->feature == 1 && $package->number_of_feature_property != -1) {

            // check total feature listing
            $totalFeatureListing = Property::where(['user_id' => auth()->user()->id, 'is_featured' => 1])->whereNot('id', $id)->count();

            if ($totalFeatureListing >= $package->number_of_feature_property) {
                return redirect()->back()->with([
                    'message' => __('You have reached the maximum number of featured listings'),
                    'alert-type' => 'error'
                ]);
            }
        }

        DB::beginTransaction();
        try {
            $data = $this->propertyService->update($request, $id, $requestData);
            DB::commit();
            return redirect()->route('website.user.property.index')->with([
                'message' => __('Saved Successfully'),
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->info($e->getMessage());
            return redirect()->back()->with([
                'message' => "Property Update Failed",
                'alert-type' => 'error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // destroy property
        $this->propertyService->destroy($id);

        return redirect()->route('website.user.property.index')->with([
            'message' => __('Deleted Successfully'),
            'alert-type' => 'success'
        ]);
    }
}
