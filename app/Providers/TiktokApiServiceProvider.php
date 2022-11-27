<?php

namespace App\Providers;

use App\Api\Nature\Tiktok\TiktokApi;
use App\Api\Nature\Tiktok\TiktokCookie;
use App\Api\ThirdParty\TikWM\TikWMApi;
use App\RequestTikTok\Cookies;
use Illuminate\Support\ServiceProvider;

class TiktokApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $cookies = Cookies::generateCookieGuzzleFromConfig(
            cookies_config: config('tiktok.tiktok_cookie') ?? [],
            cookie_domain: config('tiktok.cookie_domain')
        );

        $this->app->singleton('tiktok-api-nature', fn() => new TiktokApi(client: app('client'), cookie: $cookies));
        $this->app->singleton('tikwm', fn() => new TikWMApi(client: app('client')));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
