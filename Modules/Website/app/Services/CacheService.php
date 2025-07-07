<?php

namespace Modules\Website\app\Services;

use Illuminate\Support\Facades\Cache;
use Modules\GlobalSetting\app\Models\CustomPagination;
use Modules\GlobalSetting\app\Models\Setting;

class CacheService
{
    public function setCache()
    {
        if (request('theme')) {
            cache()->forget('selected_theme');
            cache()->forget('setting');
            cache()->remember('selected_theme', 1800, function () {
                return request('theme');
            });
        }


        Cache::rememberForever('setting', function () {
            $setting_info = Setting::get();
            $setting      = [];
            foreach ($setting_info as $setting_item) {
                $setting[$setting_item->key] = $setting_item->value;
            }


            if (cache('selected_theme') == 'auto') {
                cache()->forget('selected_theme');
            }
            if (cache()->has('selected_theme')) {
                $setting['theme'] = cache('selected_theme');
            }



            if ($setting['theme'] != 1) {
                $setting['logo'] = $setting['logo_theme_' . $setting['theme']];
            }
            return (object) $setting;
        });

        Cache::rememberForever('CustomPagination', function () {
            $custom_pagination = CustomPagination::all();
            $pagination        = [];
            foreach ($custom_pagination as $item) {
                $pagination[str_replace(' ', '_', strtolower($item?->section_name))] = $item?->item_qty;
            }
            return (object) $pagination;
        });
    }

    public function getSetting()
    {
        return Cache::get('setting');
    }

    /**
     * @param $setting
     */
    public function setTheme($setting): void
    {
        $theme = 1;

        if ($requestedTheme = request('theme')) {
            if (in_array($requestedTheme, themeList())) {
                $theme = $requestedTheme;
            }
        } elseif ($setting) {
            $settingTheme = $setting->theme ?? 1;

            if (cache()->has('selected_theme')) {
                $cachedTheme = cache('selected_theme');
                if (in_array($cachedTheme, themeList())) {
                    $theme = $cachedTheme;
                } else {
                    $theme = $settingTheme;
                }
            } else {
                $theme = $settingTheme;
            }
        }
        if (!in_array($theme, themeList())) {
            $theme = 1;
        }

        define('THEME', $theme);
    }
}
