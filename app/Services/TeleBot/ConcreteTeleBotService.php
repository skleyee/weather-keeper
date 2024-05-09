<?php

namespace App\Services\TeleBot;

use App\Models\TelegramUser;
use App\Services\Weather\WeatherService;
use WeStacks\TeleBot\Laravel\TeleBot;

class ConcreteTeleBotService implements TeleBotService
{
    public function getMessageWithWeatherForUser(TelegramUser $user)
    {

        $weatherService = app()->make(WeatherService::class);
        $currentWeather = $weatherService->getAggregatedTodaysWeather([
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
        ]);
        return TeleBot::sendMessage([
             'chat_id' => $user->tg_user_id,
             'text' => "Привет! Текущая погода в городе " . $user->city . ":\n" .
                 $currentWeather['description'] . "🌥️\n" .
                 "Температура🌡️: " . $currentWeather['temperature'] . "°, Ощущается как " . $currentWeather['temperature_feels_like'] . "°\n" .
                 "Влажность💧: " . $currentWeather['humidity'] . "%\n" .
                 "Cкорость ветра💨: " . $currentWeather['wind_speed'] . 'м/с'
         ]);
    }
}
