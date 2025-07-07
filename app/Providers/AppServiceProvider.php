<?php

namespace App\Providers;

use Exception;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\GlobalSetting\app\Models\CustomPagination;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\Website\app\Services\CacheService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(CacheService $cacheService): void
    {
        try {
            $cacheService->setCache();

            $setting = $cacheService->getSetting();

            $this->mailSetting($setting);

            $this->setupPusherConfiguration($setting);
            $this->setupTimezone($setting);
            $cacheService->setTheme($setting);
            // if (env('APP_ENV') !== 'local') {
            //     URL::forceScheme('https');
            // }
        } catch (Exception $ex) {
            $setting = null;
            Log::error($ex->getMessage());
        }



        View::composer('*', function ($view) {

            $setting = Cache::get('setting');

            $view->with('setting', $setting);
        });

        /**
         * Register custom blade directives
         * this can be used for permission or permissions check
         * this check will be perform on admin guard
         */
        $this->registerBladeDirectives();
        Paginator::useBootstrapFour();
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('adminCan', function ($permission) {
            return "<?php if(auth()->guard('admin')->user()->can({$permission})): ?>";
        });

        Blade::directive('endadminCan', function () {
            return '<?php endif; ?>';
        });
    }

    protected function mailSetting($setting)
    {
        // Setup mail configuration
        $mailConfig = [
            'transport' => 'smtp',
            'host' => $setting?->mail_host,
            'port' => $setting?->mail_port,
            'encryption' => $setting?->mail_encryption,
            'username' => $setting?->mail_username,
            'password' => $setting?->mail_password,
            'timeout' => null,
        ];

        config(['mail.mailers.smtp' => $mailConfig]);
        config(['mail.from.address' => $setting?->mail_sender_email]);
        config(['mail.from.name' => $setting?->mail_sender_name]);
    }

    protected function setupPusherConfiguration($setting): void
    {
        config(['broadcasting.connections.pusher.key' => $setting?->pusher_app_key]);
        config(['broadcasting.connections.pusher.secret' => $setting?->pusher_app_secret]);
        config(['broadcasting.connections.pusher.app_id' => $setting?->pusher_app_id]);
        config(['broadcasting.connections.pusher.options.cluster' => $setting?->pusher_app_cluster]);
        config(['broadcasting.connections.pusher.options.host' => 'api-' . $setting?->pusher_app_cluster . '.pusher.com']);
    }

    /**
     * @param $setting
     */
    protected function setupTimezone($setting): void
    {
        config(['app.timezone' => $setting?->timezone]);
    }
}
