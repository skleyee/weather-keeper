<?php

namespace App\Services\Weather;

interface WeatherService
{
   function getTodaysWeatherByCoordinates(array $coordinates);

   function getCoordinatesByCity(string $city);
}
