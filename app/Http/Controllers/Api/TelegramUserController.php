<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Weather\WeatherService;

class TelegramUserController extends Controller
{
    public function xdd(WeatherService $weatherService)
    {
        $coordinetes = [
            'latitude' => '53.266448',
            'longitude' => '34.327511'
        ];
        dd($weatherService->getCityByCoordinates($coordinetes));
    }
}
