<?php

namespace App\Providers;

use App\Services\ImageSearchService;
use App\Services\TranslateService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class ImageSearchServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(ImageSearchService::class, function ($app) {
            return new ImageSearchService($app->make(Client::class), new TranslateService());
        });
    }

    public function provides()
    {
        return [ImageSearchService::class];
    }
}
