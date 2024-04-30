<?php

use App\Services\Weather\WeatherServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    WeStacks\TeleBot\Laravel\Providers\TeleBotServiceProvider::class,
    WeatherServiceProvider::class
];
