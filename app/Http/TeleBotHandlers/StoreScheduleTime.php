<?php

namespace App\Http\TeleBotHandlers;

use App\Models\ConsoleCommandSchedule;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Handlers\CommandHandler;
use WeStacks\TeleBot\Objects\Message;

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
        $scheduleTime = $this->update->callback_query->data;

        ConsoleCommandSchedule::storeCommandScheduleTime($user->tg_user_id, $user->consoleCommandSchedule->schedule, $scheduleTime);
        return $this->answer('Отлично! Теперь погода не застанет вас врасплох! Высылаю вам текущий прогноз, чтобы показать как это будет выглядеть:)');
    }

    protected function answer(string $text)
    {
        return $this->sendMessage([
            'text' => $text,
        ]);
    }

}
