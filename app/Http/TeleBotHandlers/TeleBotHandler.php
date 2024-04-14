<?php

namespace App\Http\TeleBotHandlers;

use App\Models\TelegramUser;
use Illuminate\Support\Facades\Log;
use WeStacks\TeleBot\Handlers\CommandHandler;

class TeleBotHandler extends CommandHandler
{


    public function handle()
    {
        if ($this->trigger()) {
            Log::channel('telegram')->info($this->update->message);
            if (TelegramUser::query()->firstWhere('tg_user_id', $this->update->message->from->id)) {
                return $this->answer('Hello there again, ' . $this->update->message->from->first_name);
            }
            $this->startConversation();
            $this->answer('Big greetings to new bot user!');
        }
    }

    public function trigger(): bool
    {
        return isset($this->update->message);
    }

    public function startConversation()
    {
        TelegramUser::query()->create([
           'tg_user_id' => $this->update->message->from->id
        ]);
    }

    public function answer($text)
    {
        return $this->sendMessage([
            'text' => $text
        ]);
    }


}
