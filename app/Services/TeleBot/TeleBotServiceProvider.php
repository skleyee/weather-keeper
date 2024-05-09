<?php

namespace App\Services\TeleBot;

use Illuminate\Support\ServiceProvider;

class TeleBotServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TeleBotService::class, ConcreteTeleBotService::class);
    }
}
