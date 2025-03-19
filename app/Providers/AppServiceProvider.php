<?php

namespace App\Providers;

use App\UrlShortener\UrlEncoder;
use App\UrlShortener\UrlEncoderInterface;
use App\UrlShortener\UrlShortener;
use App\UrlShortener\UrlShortenerInterface;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UrlEncoder::class, function (Application $app) {

            /** @var Repository $config */
            $config = $app->get('config');
            return new UrlEncoder(
                $config->string('url_shorting.scheme'),
                $config->string('url_shorting.domain'),
                $config->integer('url_shorting.path_length'),
            );
        });
        $this->app->bind(UrlEncoderInterface::class, UrlEncoder::class);
        $this->app->bind(UrlShortenerInterface::class, UrlShortener::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
