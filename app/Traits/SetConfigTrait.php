<?php

namespace App\Traits;

use App\Enums\SocialiteDriverType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

trait SetConfigTrait
{
    protected static function setGoogleLoginInfo()
    {
        $setting = Cache::get('setting');
        if ($setting) {
            Config::set('services.google.client_id', $setting->gmail_client_id);
            Config::set('services.google.client_secret', $setting->gmail_secret_id);
            Config::set('services.google.redirect', route('auth.social.callback', SocialiteDriverType::GOOGLE->value));
        }
    }
}
