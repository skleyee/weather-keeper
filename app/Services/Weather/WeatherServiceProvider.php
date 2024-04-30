<?php

namespace App\Services\Weather;

use Illuminate\Support\ServiceProvider;

class WeatherServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(WeatherService::class, OpenWeather::class);
    }
}
