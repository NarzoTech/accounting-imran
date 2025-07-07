<?php

namespace Modules\Website\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\Request;
use Modules\Blog\app\Models\Blog;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\Location\app\Models\City;
use Modules\Property\app\Models\Brand;
use Modules\Property\app\Models\Property;
use Modules\Property\app\Services\PropertyService;
use Modules\SiteAppearance\app\Models\SiteSettings;
use Modules\Testimonial\app\Models\Testimonial;

class WebsiteController extends Controller
{
    use GlobalMailTrait;
    /**
     * Display a listing of the resource.
     */

    public function __construct(private PropertyService $propertyService) {}

    public function home()
    {
        $blogs = Blog::with(['translation', 'category.translation', 'comments' => function ($q) {
            return $q
                ->where('status', 1)
                ->where('parent_id', 0);
        }])->orderBy('id', 'desc')->where(['status' => 1])->where('show_homepage', 1)->get()->take(3);


        $home = 'home_' . THEME;

        $seo_setting =  SeoSetting::where('page_name', 'Home Page')->first();
        $testimonials = Testimonial::with('translation')->where('status', 1)->get();

        $baseProperties = $this->propertyService->list()->where('status', 1);
        $properties = (clone $baseProperties)->where('show_homepage', 1)->get();
        $featuredProperties = (clone $baseProperties)->where('is_featured', 1)->get();
        $maxPrice = (clone $baseProperties)->max('price');
        $minPrice = (clone $baseProperties)->min('price');
        $maxBedrooms = (clone $baseProperties)->max('number_of_bedroom');
        $maxBathrooms = (clone $baseProperties)->max('number_of_bathroom');

        $amenities = $this->propertyService->listAmenities();
        $types = $this->propertyService->listTypes();

        $agents = $this->propertyService->listAgents();
        $purposes = $this->propertyService->listPurposes();

        $cities = City::with('translation')->where('status', 1)->get();



        $priceRanges = $this->priceRange($minPrice, $maxPrice);


        $locale = session('lang');
        $cacheKey = 'siteContent_' . $locale;
        if (!cache()->has($cacheKey)) {
            $contents = SiteSettings::with('translations')->get();
            cache()->forever($cacheKey, $contents);
        }

        if (cache()->has($cacheKey)) {
            $contents = cache()->get($cacheKey);
            $contents = $contents->where('theme', THEME)->where('page_name', 'homepage');
        }

        $partners = Brand::where('status', 1)->get();

        return view('website::pages.home.' . $home, compact('blogs', 'seo_setting', 'testimonials', 'properties', 'amenities', 'types', 'agents', 'featuredProperties', 'purposes', 'cities', 'priceRanges', 'maxBedrooms', 'maxBathrooms', 'contents', 'partners'));
    }

    public function property(PropertyService $propertyService)
    {
        $propertiesList = $propertyService->list()->where('status', 1);

        $properties = clone $propertiesList;
        if (request()->keyword) {
            $properties = $properties->whereHas('translation', function ($q) {
                $q->where('title', 'like', '%' . request()->keyword . '%');
            });
        }
        if (request()->city) {
            $properties = $properties->whereHas('city', function ($q) {
                $q->where('slug', request()->city);
            });
        }

        if (request()->type) {
            $properties = $properties->whereHas('type', function ($q) {
                $q->where('slug', request()->type);
            });
        }

        if (request()->featured) {
            $properties = $properties->where('is_featured', 1);
        }

        if (request()->purpose) {
            $properties = $properties->whereHas('purpose', function ($q) {
                $q->whereHas('translation', function ($q) {
                    $q->where('name', request()->purpose);
                });
            });
        }

        if (request()->amenity) {
            $amenities = (array) request()->amenity;

            $properties = $properties->whereHas('amenities', function ($q) use ($amenities) {
                $q->whereIn('slug', $amenities);
            });
        }



        if (request()->price) {
            $price = $this->numberOnly(request()->price);
            $properties = $properties->whereBetween('price', [$price, $price + 1000]);
        }

        if (request()->bedroom) {
            $properties = $properties->where('number_of_bedroom', request()->bedroom);
        }

        if (request()->bathroom) {
            $properties = $properties->where('number_of_bathroom', request()->bathroom);
        }


        $perPage = cache('CustomPagination');
        $perPage = $perPage->property_list;

        $properties = $properties->paginate($perPage);

        $amenities = $propertyService->listAmenities();
        $types = $propertyService->listTypes();
        $purposes = $propertyService->listPurposes();
        $locations = City::where('status', 1)->get();

        $maxPrice = $propertiesList->max('price');
        $minPrice = $propertiesList->min('price');
        $maxBedrooms = $this->propertyService->list()->where('status', 1)->max('number_of_bedroom');

        $maxBathrooms = $this->propertyService->list()->where('status', 1)->max('number_of_bathroom');

        $cities = City::where('status', 1)->get();
        $priceRanges = $this->priceRange($minPrice, $maxPrice);
        $seo_setting =  SeoSetting::where('page_name', 'Property Page')->first();

        $setting = cache('setting');
        $layout = $setting->property_layout ?? 1;

        $view = "website::components.property-layout.$layout";

        return view($view, compact('properties', 'amenities', 'types', 'purposes', 'locations', 'maxPrice', 'minPrice', 'seo_setting', 'cities', 'priceRanges', 'maxBedrooms', 'maxBathrooms'));
    }

    public function propertyDetails($slug, PropertyService $propertyService)
    {
        $property = $propertyService->list()->where('status', 1)->where('slug', $slug)->firstOrFail();

        if (!$property) {
            abort(404);
        }

        $property->increment('views');

        $relatedProperties = $propertyService->list()->where('status', 1)->where('property_type_id', $property->property_type_id)->where('id', '!=', $property->id)->get();

        $seo_setting =  SeoSetting::where('page_name', 'Property Page')->first();
        return view('website::pages.utilities.property-details', compact('property', 'seo_setting', 'relatedProperties'));
    }

    public function aboutUs(PropertyService $propertyService)
    {
        $blogs = Blog::with(['translation', 'category.translation', 'comments' => function ($q) {
            return $q
                ->where('status', 1)
                ->where('parent_id', 0);
        }])->orderBy('id', 'desc')->where(['status' => 1])->where('show_homepage', 1)->get()->take(3);

        $seo_setting =  SeoSetting::where('page_name', 'About Page')->first();
        $testimonials = Testimonial::with('translation')->where('status', 1)->get();

        $locale = session('lang');
        $cacheKey = 'siteContent_' . $locale;

        if (!cache()->has($cacheKey)) {
            $contents = SiteSettings::with('translations')->get();
            cache()->forever($cacheKey, $contents);
        }

        $contents = cache()->get($cacheKey);

        $aboutSectionContent = $contents
            ->where('section_name', 'about')
            ->where('theme', THEME)
            ->first();

        $amenities = $propertyService->listAmenities();
        $agents = $this->propertyService->listAgents();
        $partners = Brand::where('status', 1)->get();

        return view('website::pages.utilities.about-us', compact('blogs', 'seo_setting', 'testimonials', 'contents', 'amenities', 'agents', 'aboutSectionContent', 'partners'));
    }

    public function contactUs()
    {
        $seo_setting =  SeoSetting::where('page_name', 'Contact Page')->first();

        $locale = session('lang');
        $cacheKey = 'siteContent_' . $locale;

        if (!cache()->has($cacheKey)) {
            $contents = SiteSettings::with('translations')->get();
            cache()->forever($cacheKey, $contents);
        }

        $contents = cache()->get($cacheKey);
        $contents = $contents->where('page_name', 'contactpage')->first();

        return view('website::pages.utilities.contact-us', compact('seo_setting', 'contents'));
    }

    public function propertyContactMessage(Request $request)
    {
        $request->validate([
            'property_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'message' => 'required',
        ]);

        $property = Property::find($request->property_id);
        $property->contacts()->create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'time' => date($request->time),
            'date' => date($request->date),
        ]);


        //mail send
        $str_replace = [
            'property_name' => $property->title,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'time' => $request->time,
            'date' => $request->date,
            'subject' => __('Property Contact Message'),
            'message' => $request->message,
        ];
        [$subject, $message] = $this->fetchEmailTemplate('property_contact_mail', $str_replace);
        $this->sendMail($property->email, $subject, $message);

        if ($request->ajax()) {
            return response()->json(['message' => __("Your message has been sent successfully."), 'status' => 'success']);
        }

        return redirect()->back()->with([
            'message' => __("Your message has been sent successfully."),
            'alert-type' => 'success'
        ]);
    }

    public function agents()
    {

        $perPage = cache('CustomPagination');
        $perPage = $perPage->agent_list;

        $agents = $this->propertyService->activeAgents()->paginate($perPage);
        $seo_setting =  SeoSetting::where('page_name', 'Agent Page')->first();
        return view('website::pages.utilities.agent', compact('agents', 'seo_setting'));
    }

    public function agentDetails($slug)
    {
        $agent = User::where('slug', $slug)->first();

        if (!$agent) {
            abort(404);
        }


        $propertiesCount = $agent->properties()->where('status', 1)->count();
        $properties = $agent->properties()->where('user_id', $agent->id)->where('status', 1)->orderBy('id', 'desc')->paginate(6);
        $seo_setting =  SeoSetting::where('page_name', 'Agent Page')->first();
        return view('website::pages.utilities.agent-details', compact('agent', 'properties', 'seo_setting', 'propertiesCount'));
    }

    private function priceRange($min, $max)
    {
        $minPrice = $min;  // Example values
        $maxPrice = $max; // Example values
        $range = $maxPrice - $minPrice;

        if ($range <= 1000) {
            $step = 100;
        } elseif ($range <= 5000) {
            $step = 500;
        } elseif ($range <= 10000) {
            $step = 1000;
        } else {
            $step = 5000;
        }

        $priceRanges = [];

        if ($minPrice == $maxPrice) {
            $priceRanges[] = currency($minPrice);
        } else {
            for ($price = $minPrice; $price < $maxPrice; $price += $step) {
                $endPrice = $price + $step - 1; // Avoid overlapping ranges
                if ($endPrice > $maxPrice) {
                    $endPrice = $maxPrice;
                }
                $priceRanges[] = currency($price) . " to " . currency($endPrice);
            }
        }

        return $priceRanges;
    }


    public function numberOnly($str)
    {
        return preg_replace("/[^0-9.]/", "", $str);
    }
}
