<?php

namespace App\Services\TeleBot;

use App\Models\TelegramUser;

interface TeleBotService
{
    public function getMessageWithWeatherForUser(TelegramUser $user);
}
