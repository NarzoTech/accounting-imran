<?php

namespace Modules\Website\app\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Property\app\Models\PropertyReview;
use Modules\Property\app\Models\Wishlist;
use Modules\Property\app\Services\AminityService;
use Modules\Property\app\Services\PropertyPurposeService;
use Modules\Property\app\Services\PropertyService;
use Modules\Property\app\Services\PropertyTypeService;
use Modules\Subscription\app\Models\SubscriptionPlan;

class UserController extends Controller
{
    public function __construct(private AminityService $aminityService, private PropertyTypeService $propertyTypeService, private PropertyPurposeService $propertyPurposeService, private PropertyService $propertyService)
    {
        $this->middleware('auth:web');
    }
    public function dashboard()
    {
        $propertiesCount = $this->propertyService->list()->where('user_id', Auth::guard('web')->user()->id)->count();

        $pendingPropertiesCount = $this->propertyService->list()->where('user_id', Auth::guard('web')->user()->id)->where('status', 0)->count();
        $wishlist = Wishlist::where('user_id', Auth::guard('web')->user()->id)->count();

        $properties = $this->propertyService->list()->where('user_id', Auth::guard('web')->user()->id)->paginate(5);

        // recent property reviews

        $reviews = PropertyReview::whereHas('property', function ($query) {
            $query->where('user_id', Auth::guard('web')->user()->id);
        })->orderBy('created_at', 'desc');
        $reviewsCount = $reviews->count();
        $recentPropertyReviews = $reviews->take(3)->get();


        return view('website::pages.user.dashboard', compact('propertiesCount', 'pendingPropertiesCount', 'wishlist', 'properties', 'recentPropertyReviews', 'reviewsCount'));
    }

    public function uploadAvatar(Request $request)
    {
        $user = Auth::guard('web')->user();
        if ($request->hasFile('image')) {

            $old_image = $user->image ?? '';
            $image = file_upload($request->image, oldFile: $old_image);

            $user->image = $image;
            $user->save();
        }

        $notification = __('Update Successfully');
        $notification = array('message' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
    public function profile()
    {
        return view('website::pages.user.profile');
    }
    public function pricingPlan()
    {
        $subscriptionPlans = SubscriptionPlan::where('status', 1)->orderBy('serial', 'asc')->get();
        return view('website::pages.user.pricing-plan', compact('subscriptionPlans'));
    }

    public function order()
    {
        $orders = Auth::user()->orders()->latest()->paginate(20);
        return view('website::pages.user.order', compact('orders'));
    }

    public function wishlist()
    {
        $wishlists = Auth::user()->wishlists()->latest()->paginate(20);

        return view('website::pages.user.wishlist', compact('wishlists'));
    }

    public function storeWishlist(Request $request)
    {
        $user = Auth::user();

        $wishlist = $user->wishlists()->where('property_id', $request->property_id)->first();

        if ($wishlist) {
            if ($request->ajax()) {
                return response()->json(['message' => __('Property already added to wishlist!')]);
            }
        }

        $user->wishlists()->create([
            'property_id' => $request->property_id,
        ]);


        $notification = __("Property added to wishlist!");
        $notification = array('message' => $notification, 'status' => 'success');

        if ($request->ajax()) {
            return response()->json($notification);
        }

        return redirect()->back()->with($notification);
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'property_id' => 'required',
            'rating' => 'required',
            'comment' => 'required',
        ]);

        $user = Auth::user();

        // check if user has already submitted a review for this property
        $review = $user->reviews()->where('property_id', $request->property_id)->first();

        if ($review) {
            $notification = __("You have already submitted a review for this property.");
            $notification = array('message' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        $user->reviews()->create([
            'property_id' => $request->property_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 1
        ]);

        $notification = __("Review submitted successfully!");
        $notification = array('message' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function deleteWishlist($id)
    {
        $wishlist = Auth::user()->wishlists()->where('id', $id)->first();
        $wishlist->delete();
        $notification = __("Property removed from wishlist!");
        $notification = array('message' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function reviews()
    {

        $reviews = PropertyReview::with('property', 'user')->whereHas('property', function ($query) {
            $query->where('user_id', Auth::guard('web')->user()->id);
        })->orderBy('created_at', 'desc')->paginate(20);
        return view('website::pages.user.reviews', compact('reviews'));
    }

    public function invoice($id)
    {
        $order = Auth::user()->orders()->where('order_id', $id)->first();
        if (!$order) {
            return redirect()->back()->with([
                'message' => 'Order not found',
                'alert-type' => 'error'
            ]);
        }
        return view('website::pages.user.invoice', compact('order'));
    }

    public function updatePassword(Request $request)
    {
        $validatedData = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:4|confirmed',
        ]);

        if (!(Hash::check($request->current_password, Auth::user()->password))) {
            // The passwords matches
            $notification = __("Your current password does not matches with the password.");
            $notification = array('message' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        if (strcmp($request->current_password, $request->password) == 0) {
            // Current password and new password same
            $notification = __("New Password cannot be same as your current password.");
            $notification = array('message' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        $notification = __("Password successfully changed!");
        $notification = array('message' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('image')) {

            $old_image = $user->image ?? '';
            $image = file_upload($request->image, oldFile: $old_image);

            $user->image = $image;
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->facebook = $request->facebook;
        $user->linkedin = $request->linkedin;
        $user->twitter = $request->twitter;
        $user->website = $request->website;
        $user->designation = $request->designation;
        $user->whatsapp_number = $request->whatsapp_number;
        $user->profession_start = $request->profession_start;
        $user->about = $request->about;

        $user->save();
        $notification = __("Profile successfully updated!");
        $notification = array('message' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
}
