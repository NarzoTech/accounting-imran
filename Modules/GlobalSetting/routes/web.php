<?php

use Illuminate\Support\Facades\Route;
use Modules\GlobalSetting\app\Http\Controllers\EmailSettingController;
use Modules\GlobalSetting\app\Http\Controllers\GlobalSettingController;
use Modules\GlobalSetting\app\Http\Controllers\ManageAddonController;

Route::group(['as' => 'admin.', 'prefix' => 'admin/settings', 'middleware' => ['auth:admin', 'translation']], function () {

    Route::controller(GlobalSettingController::class)->group(function () {

        Route::get('general-setting', 'general_setting')->name('general-setting');
        Route::put('update-general-setting', 'update_general_setting')->name('update-general-setting');

        Route::put('update-logo-favicon', 'update_logo_favicon')->name('update-logo-favicon');
        Route::put('update-admin-logo-favicon', 'update_admin_logo_favicon')->name('update-admin-logo-favicon');
        Route::put('update-cookie-consent', 'update_cookie_consent')->name('update-cookie-consent');
        Route::put('update-custom-pagination', 'update_custom_pagination')->name('update-custom-pagination');
        Route::put('update-default-avatar', 'update_default_avatar')->name('update-default-avatar');
        Route::put('update-breadcrumb', 'update_breadcrumb')->name('update-breadcrumb');
        Route::put('update-copyright-text', 'update_copyright_text')->name('update-copyright-text');
        Route::put('update-maintenance-mode-status', 'update_maintenance_mode_status')->name('update-maintenance-mode-status');

        Route::get('maintenance', 'maintenance')->name('maintenance');
        Route::put('update-maintenance-mode', 'update_maintenance_mode')->name('update-maintenance-mode');
        Route::get('cookie', 'cookie_consent')->name('cookie');

        Route::get('pagination', 'pagination')->name('pagination');
        Route::get('date-time', 'date_time')->name('admin-date-time');
        Route::get('admin-setting', 'admin_setting')->name('admin-setting');
        Route::get('website-setting', 'website_setting')->name('website-setting');

        Route::get('seo-setting', 'seo_setting')->name('seo-setting');
        Route::put('update-seo-setting/{id}', 'update_seo_setting')->name('update-seo-setting');

        Route::get('credential-setting', 'crediential_setting')->name('credential-setting');
        Route::put('update-google-captcha', 'update_google_captcha')->name('update-google-captcha');
        Route::put('update-google-tag', 'update_google_tag')->name('update-google-tag');
        Route::put('update-tawk-chat', 'update_tawk_chat')->name('update-tawk-chat');
        Route::put('update-google-analytic', 'update_google_analytic')->name('update-google-analytic');
        Route::put('update-facebook-pixel', 'update_facebook_pixel')->name('update-facebook-pixel');
        Route::put('update-social-login', 'update_social_login')->name('update-social-login');
        Route::put('update-pusher', 'update_pusher')->name('update-pusher');

        Route::get('cache-clear', 'cache_clear')->name('cache-clear');
        Route::post('cache-clear', 'cache_clear_confirm')->name('cache-clear-confirm');
        Route::get('system/cleanup', 'database_clear')->name('system.cleanup');
        Route::delete('database-clear-success', 'database_clear_success')->name('system.cleanup-success');
        Route::get('custom-code/{type}', 'customCode')->name('custom-code');
        Route::post('update-custom-code', 'customCodeUpdate')->name('update-custom-code');

        Route::get('system-update', 'systemUpdate')->name('system-update.index');
        Route::post('system-update/check', 'systemUpdateCheck')->name('system-update.check');
        Route::post('system-update/store', 'systemUpdateStore')->name('system-update.store');
        Route::post('system-update/redirect', 'systemUpdateRedirect')->name('system-update.redirect');
        Route::delete('system-update/delete', 'systemUpdateDelete')->name('system-update.delete');

        Route::get('cron-jobs', 'cronJobs')->name('cron-jobs');
        Route::get('blog/settings/', 'blogSettings')->name('blog.settings');
    });

    Route::controller(EmailSettingController::class)->group(function () {

        Route::get('email-settings', 'email_config')->name('email-settings');
        Route::put('update-email-settings', 'update_email_config')->name('update-email-settings');

        Route::get('edit-email-template/{id}', 'edit_email_template')->name('edit-email-template');
        Route::put('update-email-template/{id}', 'update_email_template')->name('update-email-template');

        Route::post('test/mail/credentials', 'test_mail_credentials')->name('test-mail-credentials');
    });
});
