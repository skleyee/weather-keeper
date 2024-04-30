<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Weather\WeatherService;

class TelegramUserController extends Controller
{
    public function xdd(WeatherService $weatherService)
    {
        $coordinetes = $weatherService->getCoordinatesByCity('London');
        dd($weatherService->getTodaysWeatherByCoordinates($coordinetes));
    }
}
