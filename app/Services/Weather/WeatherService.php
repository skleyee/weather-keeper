<?php

namespace App\Services\Weather;

interface WeatherService
{
    public function getTodaysWeatherByCoordinates(array $coordinates);

    public function getCoordinatesByCity(string $city);
}
