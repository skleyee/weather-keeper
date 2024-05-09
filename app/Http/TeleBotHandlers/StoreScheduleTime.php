<?php

namespace App\Http\TeleBotHandlers;

use App\Models\ConsoleCommandSchedule;
use App\Services\TeleBot\TeleBotService;
use WeStacks\TeleBot\Handlers\CommandHandler;

class StoreScheduleTime extends CommandHandler
{
    use WorksWIthTgUser;

    public function trigger(): bool
    {
        return isset($this->update->callback_query) && ! isset($this->update->chat_join_request);
    }

    public function handle()
    {
        $user = $this->firstOrCreateTgUser($this->update->callback_query->from->id);
        $botService = app()->make(TeleBotService::class);
        $scheduleTime = $this->update->callback_query->data;

        ConsoleCommandSchedule::storeCommandScheduleTime($user->tg_user_id, $user->consoleCommandSchedule->schedule, $scheduleTime);
        $this->answer('Отлично! Теперь погода не застанет вас врасплох! Высылаю вам текущий прогноз, чтобы показать как это будет выглядеть:)');
        return $botService->getMessageWithWeatherForUser($user);
    }

    protected function answer(string $text)
    {
        return $this->sendMessage([
            'text' => $text,
        ]);
    }

}
