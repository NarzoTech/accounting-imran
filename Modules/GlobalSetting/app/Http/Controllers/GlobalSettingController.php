<?php

namespace Modules\GlobalSetting\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Blog\app\Models\BlogCategoryTranslation;
use Modules\Blog\app\Models\BlogComment;
use Modules\Blog\app\Models\BlogTranslation;
use Modules\ContactMessage\app\Models\ContactMessage;
use Modules\GlobalSetting\app\Enums\WebsiteSettingEnum;
use Modules\GlobalSetting\app\Models\CustomCode;
use Modules\GlobalSetting\app\Models\CustomPagination;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\Location\app\Models\City;
use Modules\Location\app\Models\CityTranslation;
use Modules\Location\app\Models\Country;
use Modules\Location\app\Models\CountryTranslation;
use Modules\Location\app\Models\State;
use Modules\Location\app\Models\StateTranslation;
use Modules\NewsLetter\app\Models\NewsLetter;
use Modules\Order\app\Models\Order;
use Modules\PageBuilder\app\Models\CustomizablePageTranslation;
use Modules\PageBuilder\app\Models\CustomizeablePage;
use Modules\Property\app\Models\Amenity;
use Modules\Property\app\Models\AmenityTranslation;
use Modules\Property\app\Models\Brand;
use Modules\Property\app\Models\ProertyAmenity;
use Modules\Property\app\Models\Property;
use Modules\Property\app\Models\PropertyAmenity;
use Modules\Property\app\Models\PropertyContact;
use Modules\Property\app\Models\PropertyPurpose;
use Modules\Property\app\Models\PropertyPurposeTranslation;
use Modules\Property\app\Models\PropertyReview;
use Modules\Property\app\Models\PropertyTranslation;
use Modules\Property\app\Models\PropertyType;
use Modules\Property\app\Models\PropertyTypeTranslation;
use Modules\Property\app\Models\Wishlist;
use Modules\Testimonial\app\Models\Testimonial;
use Modules\Testimonial\app\Models\TestimonialTranslation;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use ZipArchive;

class GlobalSettingController extends Controller
{
    /**
     * @var mixed
     */
    protected $cachedSetting;

    public function __construct()
    {
        $this->cachedSetting = Cache::get('setting');
    }

    public function general_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');

        return view('globalsetting::settings.index');
    }

    public function update_general_setting(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        $request->validate([
            'app_name' => 'sometimes',
            'timezone' => 'sometimes',
            'contact_message_receiver_mail' => 'sometimes|email',
            'is_queable' => 'sometimes|in:active,inactive',
            'comments_auto_approved' => 'sometimes|in:active,inactive',
            'search_engine_indexing' => 'sometimes|in:active,inactive',
        ], [
            'app_name.required' => __('App name is required'),
            'timezone.required' => __('Timezone is required'),
            'is_queable.required' => __('Queue is required'),
            'contact_message_receiver_mail.email' => __('The contact message receiver mail must be a valid email address.'),
            'is_queable.in' => __('Queue is invalid'),
            'comments_auto_approved.in' => __('Review auto approved is invalid'),
            'search_engine_indexing.in' => __('Search engine crawling is invalid'),
        ]);

        foreach ($request->except('_token', 'default_avatar') as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }


        if ($request->file('default_avatar')) {
            $file_name = file_upload($request->default_avatar, 'uploads/custom-images/', $this->cachedSetting?->default_avatar);
            Setting::where('key', 'default_avatar')->update(['value' => $file_name]);
        }

        Cache::forget('setting');
        Cache::forget('corn_working');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        if ($request->ajax()) {
            return response()->json($notification);
        }

        return redirect()->back()->with($notification);
    }

    public function date_time()
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $all_timezones = WebsiteSettingEnum::allTimeZones();
        $all_time_format = WebsiteSettingEnum::allTimeFormat();
        $all_date_format = WebsiteSettingEnum::allDateFormat();
        return view('globalsetting::date-time', compact('all_timezones', 'all_time_format', 'all_date_format'));
    }

    public function pagination()
    {
        checkAdminHasPermissionAndThrowException('setting.view');
        $custom_paginations = CustomPagination::all();
        return view('globalsetting::pagination', compact('custom_paginations'));
    }

    public function update_logo_favicon(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        if (THEME != 1) {
            $logo = 'logo_theme_' . THEME;
        } else {
            $logo = 'logo';
        }
        if ($request->hasFile('logo')) {
            $file_name = file_upload($request->logo, 'uploads/website-images/', $this->cachedSetting?->$logo);

            Setting::where('key', $logo)->update(['value' => $file_name]);
        }

        if ($request->file('favicon')) {
            $file_name = file_upload($request->favicon, 'uploads/custom-images/', $this->cachedSetting?->favicon);
            Setting::where('key', 'favicon')->update(['value' => $file_name]);
        }

        if ($request->file('footer_logo')) {
            $file_name = file_upload($request->footer_logo, 'uploads/custom-images/', $this->cachedSetting?->footer_logo);
            Setting::where('key', 'footer_logo')->update(['value' => $file_name]);
        }


        if ($request->file('auth_image')) {
            $file_name = file_upload($request->auth_image, 'uploads/custom-images/', $this->cachedSetting?->auth_image ?? '');
            Setting::updateOrCreate(['key' => 'auth_image'], ['value' => $file_name]);
        }
        if ($request->file('breadcrumb_image')) {
            $file_name = file_upload($request->breadcrumb_image, 'uploads/custom-images/', $this->cachedSetting?->breadcrumb_image ?? '');
            Setting::updateOrCreate(['key' => 'breadcrumb_image'], ['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function admin_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');

        return view('globalsetting::admin-logo-favicon');
    }

    public function update_admin_logo_favicon(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        if ($request->file('admin_logo')) {
            $file_name = file_upload($request->admin_logo, 'uploads/custom-images/', $this->cachedSetting?->admin_logo ?? '');
            Setting::updateOrCreate(['key' => 'admin_logo'], ['value' => $file_name]);
        }

        if ($request->file('admin_favicon')) {
            $file_name = file_upload($request->admin_favicon, 'uploads/custom-images/', $this->cachedSetting?->admin_favicon ?? '');
            Setting::updateOrCreate(['key' => 'admin_favicon'], ['value' => $file_name]);
        }
        if ($request->file('admin_login_image')) {
            $file_name = file_upload($request->admin_login_image, 'uploads/custom-images/', $this->cachedSetting?->admin_login_image ?? '');
            Setting::updateOrCreate(['key' => 'admin_login_image'], ['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function website_setting()
    {
        return view('globalsetting::website-images');
    }


    public function cookie_consent()
    {
        return view('globalsetting::cookie');
    }

    public function update_cookie_consent(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'cookie_status' => 'required',
            'border' => 'required',
            'corners' => 'required',
            'background_color' => 'required',
            'text_color' => 'required',
            'border_color' => 'required',
            'btn_bg_color' => 'required',
            'btn_text_color' => 'required',
            'link_text' => 'required',
            'btn_text' => 'required',
            'message' => 'required',
        ], [
            'cookie_status.required' => __('Status is required'),
            'border.required' => __('Border is required'),
            'corners.required' => __('Corner is required'),
            'background_color.required' => __('Background color is required'),
            'text_color.required' => __('Text color is required'),
            'border_color.required' => __('Border Color is required'),
            'btn_bg_color.required' => __('Button color is required'),
            'btn_text_color.required' => __('Button text color is required'),
            'link_text.required' => __('Link text is required'),
            'btn_text.required' => __('Button text is required'),
            'message.required' => __('Message is required'),
        ]);

        Setting::where('key', 'cookie_status')->update(['value' => $request->cookie_status]);
        Setting::where('key', 'border')->update(['value' => $request->border]);
        Setting::where('key', 'corners')->update(['value' => $request->corners]);
        Setting::where('key', 'background_color')->update(['value' => $request->background_color]);
        Setting::where('key', 'text_color')->update(['value' => $request->text_color]);
        Setting::where('key', 'border_color')->update(['value' => $request->border_color]);
        Setting::where('key', 'btn_bg_color')->update(['value' => $request->btn_bg_color]);
        Setting::where('key', 'btn_text_color')->update(['value' => $request->btn_text_color]);
        Setting::where('key', 'link_text')->update(['value' => $request->link_text]);
        Setting::where('key', 'btn_text')->update(['value' => $request->btn_text]);
        Setting::where('key', 'message')->update(['value' => $request->message]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_custom_pagination(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        foreach ($request->quantities as $index => $quantity) {
            if ($request->quantities[$index] == '') {
                $notification = [
                    'message' => __('Every field are required'),
                    'alert-type' => 'error',
                ];

                return redirect()->back()->with($notification);
            }

            $custom_pagination = CustomPagination::find($request->ids[$index]);
            $custom_pagination->item_qty = $request->quantities[$index];
            $custom_pagination->save();
        }

        // Cache update
        $custom_pagination = CustomPagination::all();
        $pagination = [];
        foreach ($custom_pagination as $item) {
            $pagination[str_replace(' ', '_', strtolower($item->section_name))] = $item->item_qty;
        }
        $pagination = (object) $pagination;
        Cache::put('CustomPagination', $pagination);

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_breadcrumb(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        if ($request->file('breadcrumb_image')) {
            $file_name = file_upload($request->breadcrumb_image, 'uploads/custom-images/', $this->cachedSetting?->breadcrumb_image);
            Setting::where('key', 'breadcrumb_image')->update(['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_copyright_text(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'copyright_text' => 'required',
        ], [
            'copyright_text' => __('Copyright Text is required'),
        ]);
        Setting::where('key', 'copyright_text')->update(['value' => $request->copyright_text]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function crediential_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');

        return view('globalsetting::credientials.index');
    }

    public function update_google_captcha(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'recaptcha_site_key' => 'required',
            'recaptcha_secret_key' => 'required',
            'recaptcha_status' => 'required',
        ], [
            'recaptcha_site_key.required' => __('Site key is required'),
            'recaptcha_secret_key.required' => __('Secret key is required'),
            'recaptcha_status.required' => __('Status is required'),
        ]);

        Setting::where('key', 'recaptcha_site_key')->update(['value' => $request->recaptcha_site_key]);
        Setting::where('key', 'recaptcha_secret_key')->update(['value' => $request->recaptcha_secret_key]);
        Setting::where('key', 'recaptcha_status')->update(['value' => $request->recaptcha_status]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_google_tag(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'googel_tag_status' => 'required',
            'googel_tag_id' => 'required',
        ], [
            'googel_tag_status.required' => __('Status is required'),
            'googel_tag_id.required' => __('Google Tag ID is required'),
        ]);

        Setting::where('key', 'googel_tag_status')->update(['value' => $request->googel_tag_status]);
        Setting::where('key', 'googel_tag_id')->update(['value' => $request->googel_tag_id]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_tawk_chat(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'tawk_status' => 'required',
            'tawk_chat_link' => 'required',
        ], [
            'tawk_status.required' => __('Status is required'),
            'tawk_chat_link.required' => __('Chat link is required'),
        ]);
        if (strpos($request->tawk_chat_link, 'embed.tawk.to') !== false) {
            $embedUrl = $request->tawk_chat_link;
        } elseif (strpos($request->tawk_chat_link, 'tawk.to/chat') !== false) {
            $embedUrl = str_replace('tawk.to/chat', 'embed.tawk.to', $request->tawk_chat_link);
        } else {
            $embedUrl = 'https://embed.tawk.to/' . $request->tawk_chat_link;
        }

        Setting::where('key', 'tawk_status')->update(['value' => $request->tawk_status]);
        Setting::where('key', 'tawk_chat_link')->update(['value' => $embedUrl]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_google_analytic(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'google_analytic_status' => 'required',
            'google_analytic_id' => 'required',
        ], [
            'google_analytic_status.required' => __('Status is required'),
            'google_analytic_id.required' => __('Analytic id is required'),
        ]);

        Setting::where('key', 'google_analytic_status')->update(['value' => $request->google_analytic_status]);
        Setting::where('key', 'google_analytic_id')->update(['value' => $request->google_analytic_id]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_facebook_pixel(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'pixel_status' => 'required',
            'pixel_app_id' => 'required',
        ], [
            'pixel_status.required' => __('Status is required'),
            'pixel_app_id.required' => __('App ID is required'),
        ]);

        Setting::where('key', 'pixel_status')->update(['value' => $request->pixel_status]);
        Setting::where('key', 'pixel_app_id')->update(['value' => $request->pixel_app_id]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_social_login(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $rules = [
            'google_login_status' => 'required',
            'gmail_client_id' => 'required',
            'gmail_secret_id' => 'required',
        ];
        $customMessages = [
            'google_login_status.required' => __('Google is required'),
            'gmail_client_id.required' => __('Google client is required'),
            'gmail_secret_id.required' => __('Google secret is required'),
        ];
        $request->validate($rules, $customMessages);

        Setting::where('key', 'facebook_login_status')->update(['value' => $request->facebook_login_status]);
        Setting::where('key', 'facebook_app_id')->update(['value' => $request->facebook_app_id]);
        Setting::where('key', 'facebook_app_secret')->update(['value' => $request->facebook_app_secret]);
        Setting::where('key', 'facebook_redirect_url')->update(['value' => $request->facebook_redirect_url]);
        Setting::where('key', 'google_login_status')->update(['value' => $request->google_login_status]);
        Setting::where('key', 'gmail_client_id')->update(['value' => $request->gmail_client_id]);
        Setting::where('key', 'gmail_secret_id')->update(['value' => $request->gmail_secret_id]);
        Setting::where('key', 'gmail_redirect_url')->update(['value' => $request->gmail_redirect_url]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_pusher(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'pusher_status' => 'required',
            'pusher_app_id' => 'required',
            'pusher_app_key' => 'required',
            'pusher_app_secret' => 'required',
            'pusher_app_cluster' => 'required',
        ], [
            'pusher_status.required' => __('Status is required'),
            'pusher_app_id.required' => __('Pusher App ID is required'),
            'pusher_app_key.required' => __('Pusher App Key is required'),
            'pusher_app_secret.required' => __('Pusher App Secret is required'),
            'pusher_app_cluster.required' => __('Pusher App Cluster is required'),
        ]);

        Setting::where('key', 'pusher_status')->update(['value' => $request->pusher_status]);
        Setting::where('key', 'pusher_app_id')->update(['value' => $request->pusher_app_id]);
        Setting::where('key', 'pusher_app_key')->update(['value' => $request->pusher_app_key]);
        Setting::where('key', 'pusher_app_secret')->update(['value' => $request->pusher_app_secret]);
        Setting::where('key', 'pusher_app_cluster')->update(['value' => $request->pusher_app_cluster]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function seo_setting()
    {
        checkAdminHasPermissionAndThrowException('setting.view');
        $pages = SeoSetting::all();

        return view('globalsetting::seo_setting', compact('pages'));
    }

    public function update_seo_setting(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $rules = [
            'seo_title' => 'required',
            'seo_description' => 'required',
        ];
        $customMessages = [
            'seo_title.required' => __('SEO title is required'),
            'seo_description.required' => __('SEO description is required'),
        ];
        $request->validate($rules, $customMessages);

        $page = SeoSetting::find($id);
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->save();

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function cache_clear()
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        return view('globalsetting::cache_clear');
    }

    public function cache_clear_confirm()
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        Artisan::call('optimize:clear');

        $notification = __('Cache cleared successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function database_clear()
    {
        checkAdminHasPermissionAndThrowException('setting.view');

        return view('globalsetting::database_clear');
    }

    public function database_clear_success(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');


        // truncate all model here

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Amenity::truncate();
        AmenityTranslation::truncate();
        Blog::truncate();
        BlogTranslation::truncate();
        BlogCategory::truncate();
        BlogCategoryTranslation::truncate();
        BlogComment::truncate();
        ContactMessage::truncate();

        Brand::truncate();
        Property::truncate();
        PropertyAmenity::truncate();
        PropertyContact::truncate();
        PropertyPurpose::truncate();
        PropertyPurposeTranslation::truncate();
        PropertyReview::truncate();
        PropertyTranslation::truncate();
        PropertyType::truncate();
        PropertyTypeTranslation::truncate();
        Wishlist::truncate();

        City::truncate();
        CityTranslation::truncate();
        Country::truncate();
        CountryTranslation::truncate();
        State::truncate();
        StateTranslation::truncate();
        Order::truncate();
        NewsLetter::truncate();
        Testimonial::truncate();
        TestimonialTranslation::truncate();
        User::truncate();
        Wishlist::truncate();
        CustomizeablePage::truncate();
        CustomizablePageTranslation::truncate();


        // enable check foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->deleteFiles();

        Artisan::call('optimize:clear');

        $notification = __('Database Cleared Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];


        return redirect()->back()->with($notification);
    }
    public function deleteFiles(): void
    {
        $path = public_path('uploads');

        // delete this folder
        File::deleteDirectory($path . '/custom-images');

        // create directory and give permission
        File::makeDirectory($path, 0777, true, true);
    }

    public function customCode($type)
    {
        checkAdminHasPermissionAndThrowException('setting.view');
        $customCode = CustomCode::first();
        if (! $customCode) {
            $customCode = new CustomCode();
            $customCode->css = '//write your css code here without the style tag';
            $customCode->header_javascript = '//write your javascript here without the script tag';
            $customCode->body_javascript = '//write your javascript here without the script tag';
            $customCode->footer_javascript = '//write your javascript here without the script tag';
            $customCode->save();
        }

        return view('globalsetting::custom_code_' . $type, compact('customCode'));
    }

    public function customCodeUpdate(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $validatedData = $request->validate([
            'css' => 'sometimes',
            'header_javascript' => 'sometimes',
            'body_javascript' => 'sometimes',
            'footer_javascript' => 'sometimes',
        ]);

        $customCode = CustomCode::firstOrNew();
        $customCode->fill($validatedData);
        $customCode->save();

        Cache::forget('customCode');

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function maintenance()
    {
        checkAdminHasPermissionAndThrowException('setting.view');

        return view('globalsetting::maintenance');
    }
    public function update_maintenance_mode_status()
    {
        checkAdminHasPermissionAndThrowException('setting.update');
        $status = $this->cachedSetting?->maintenance_mode == 1 ? 0 : 1;

        Setting::where('key', 'maintenance_mode')->update(['value' => $status]);

        Cache::forget('setting');

        return response()->json([
            'success' => true,
            'message' => __('Updated Successfully'),
        ]);
    }

    public function update_maintenance_mode(Request $request)
    {
        checkAdminHasPermissionAndThrowException('setting.update');

        $request->validate([
            'maintenance_image' => 'nullable',
            'maintenance_title' => 'required',
            'maintenance_description' => 'required',
        ], [
            'maintenance_image' => __('Maintenance Mode Image is required'),
            'maintenance_title' => __('Maintenance Mode Title is required'),
            'maintenance_description' => __('Maintenance Mode Description is required'),
        ]);

        if ($request->file('maintenance_image')) {
            $file_name = file_upload($request->maintenance_image, 'uploads/custom-images/', $this->cachedSetting?->maintenance_image);
            Setting::where('key', 'maintenance_image')->update(['value' => $file_name]);
        }

        Setting::where('key', 'maintenance_mode')->update(['value' => $request->maintenance_mode]);
        Setting::where('key', 'maintenance_title')->update(['value' => $request->maintenance_title]);
        Setting::where('key', 'maintenance_description')->update(['value' => $request->maintenance_description]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function systemUpdate()
    {
        $zipLoaded = extension_loaded('zip');

        $updateFileDetails = false;
        $files = false;
        $uploadFileSize = false;

        $zipFilePath = public_path('upload/update.zip');
        if ($updateFileDetails = File::exists($zipFilePath)) {
            $uploadFileSize = File::size($zipFilePath);

            $files = $this->getFilesFromZip($zipFilePath);
        }


        $upload_max_filesize = $this->convertPHPSizeToBytes(ini_get('upload_max_filesize'));
        $post_max_size = $this->convertPHPSizeToBytes(ini_get('post_max_size'));
        $max_upload_size = min($upload_max_filesize, $post_max_size);

        return view('globalsetting::auto-update', compact('updateFileDetails', 'uploadFileSize', 'files', 'zipLoaded', 'max_upload_size'));
    }

    public function systemUpdateStore(Request $request)
    {
        $receiver = new FileReceiver("zip_file", $request, HandlerFactory::classFromRequest($request));

        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        $save = $receiver->receive();
        if ($save->isFinished()) {
            $file = $save->getFile();

            $mime = $file->getClientOriginalExtension();
            if ($mime == 'zip') {
                $filePath = public_path('upload/update.zip');
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                $file->move(public_path('upload'), 'update.zip');
            }

            return response()->json(['success' => true, 'message' => __('File saved successfully')], 200);
        }
        $handler = $save->handler();
        return response()->json([
            "message"   => $handler->getPercentageDone(),
            'success' => true,
        ]);
    }
    private function convertPHPSizeToBytes($size)
    {
        $suffix = strtoupper(substr($size, -1));
        $value = (int) substr($size, 0, -1);
        switch ($suffix) {
            case 'G':
                $value *= 1024 * 1024 * 1024;
                break;
            case 'M':
                $value *= 1024 * 1024;
                break;
            case 'K':
                $value *= 1024;
                break;
        }
        return $value;
    }
    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $index = floor(log($size, 1024));
        $formattedSize = $size / pow(1024, $index);
        return round($formattedSize, $precision) . ' ' . $units[$index];
    }

    public function systemUpdateCheck()
    {
        $settings = cache('setting');
        $project = $settings->project;
        $version = $settings->version;
        $apiUrl = env('UPDATE_URL');
        $apiUrl = $apiUrl . '/api/download-zip?project=' . $project . '&curr_version=' . $version;

        $client = new Client();
        $response = $client->get($apiUrl, [
            'http_errors' => false,
        ]);
        dd($response->getBody()->getContents());

        if ($response->getStatusCode() == 200) {
            $filePath = public_path('uploads/download.zip');
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }

            // Save binary data to a file
            file_put_contents($filePath, $response->getBody()->getContents());
            return response()->json(['message' => 'File saved successfully', 'path' => url('uploads/download.zip')]);
        }
    }

    public function systemUpdateRedirect()
    {
        $zipFilePath = public_path('upload/update.zip');

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) !== true) {
            File::delete($zipFilePath);
            $notification = __('Corrupted Zip File');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            $zip->close();
            return redirect()->back()->with($notification);
        }

        if (!$this->isFirstDirUpload($zipFilePath)) {
            $notification = __('Invalid Update File Structure');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            $zip->close();
            return redirect()->back()->with($notification);
        }

        $zip->close();

        $this->deleteFolderAndFiles(base_path('update'));

        if ($zip->open($zipFilePath) === true) {
            $zip->extractTo(base_path());
            $zip->close();
        }

        return redirect(url('/update'));
    }

    public function systemUpdateDelete()
    {
        $zipFilePath = public_path('upload/update.zip');
        File::delete($zipFilePath);

        $this->deleteFolderAndFiles(base_path('update'));

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return back()->with($notification);
    }

    private function getFilesFromZip($zipFilePath)
    {
        $files = [];
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileInfo = $zip->statIndex($i);
                $files[] = $fileInfo['name'];
            }
        }
        $zip->close();
        return $files;
    }

    private function deleteFolderAndFiles($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir . '/' . $file;

            if (is_dir($path)) {
                $this->deleteFolderAndFiles($path);
            } else {
                unlink($path);
            }
        }

        rmdir($dir);
    }

    private function isFirstDirUpload($zipFilePath)
    {
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === TRUE) {
            $firstDir = null;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileInfo = $zip->statIndex($i);
                $filePathParts = explode('/', $fileInfo['name']);

                if (count($filePathParts) > 1) {
                    $firstDir = $filePathParts[0];
                    break;
                }
            }

            $zip->close();
            return $firstDir === "update";
        }

        $zip->close();
        return false;
    }


    public function  cronJobs()
    {
        return view('globalsetting::cronjob');
    }

    public function blogSettings()
    {
        return view('globalsetting::blog-settings');
    }
}
