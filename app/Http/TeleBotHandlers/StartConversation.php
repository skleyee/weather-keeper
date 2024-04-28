<?php

namespace App\Http\TeleBotHandlers;

use App\Models\TelegramUser;
use App\Enums\District;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Facades\Log;
use WeStacks\TeleBot\Handlers\CommandHandler;
use WeStacks\TeleBot\Objects\Message;

class StartConversation extends CommandHandler
{
    use WorksWIthTgUser;

    public function trigger(): bool
    {
        return isset($this->update->message) && $this->isThisTextMessage() && ! isset($this->update->chat_join_request);
    }

    public function handle()
    {
        if (!(TelegramUser::query()->firstWhere('tg_user_id', $this->update->message->chat->id)) || $this->update->message->text == '/start') {
            $this->firstOrCreateTgUser($this->update->message->chat->id);
            return $this->answerWithButton('Привет! В каком районе Брянска ты живёшь?', $this->getButtonWithDistricts());
        }
    }

    protected function isThisTextMessage(): bool
    {
        return isset($this->update->message->text);
    }

    protected function answerWithButton(string $text, array $button): Message|PromiseInterface
    {
        return $this->sendMessage([
            'text' => $text,
            'reply_markup' => $button
        ]);
    }

    protected function getButtonWithDistricts()
    {
        return [
            'keyboard' => [
                [
                    [
                        'text' => District::getReadableOption(District::Soviet),
                    ],
                    [
                        'text' => District::getReadableOption(District::Bezhitsky),
                    ],
                    [
                        'text' => District::getReadableOption(District::Volodarsky),
                    ],
                    [
                        'text' => District::getReadableOption(District::Fokinsky),
                    ],
                ]
            ],
            'one_time_keyboard' => true,
            'resize_keyboard' => true
        ];
    }


}
