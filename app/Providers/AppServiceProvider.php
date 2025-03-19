<?php

namespace App\Providers;

use App\UrlShortener\UrlEncoder;
use App\UrlShortener\UrlEncoderInterface;
use App\UrlShortener\CachedUrlShortener;
use App\UrlShortener\UrlShortenerInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UrlEncoderInterface::class, function (Application $app) {
            $config = $app->get(Repository::class);

            return new UrlEncoder(
                $config->string('url_shorting.scheme'),
                $config->string('url_shorting.domain'),
                $config->integer('url_shorting.path_length'),
            );
        });
        $this->app->bind(UrlShortenerInterface::class, CachedUrlShortener::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
