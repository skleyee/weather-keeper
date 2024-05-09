<?php

namespace App\Http\TeleBotHandlers;

use App\Models\TelegramUser;
use GuzzleHttp\Promise\PromiseInterface;
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
            return $this->answerWithButton('Привет! Отправь своё местоположение, чтобы я мог давать тебе актуальный прогноз погоды:)', $this->getLocationButton());
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

    protected function getLocationButton(): array
    {
        return [
            'keyboard' => [
                [
                    ['text' => 'Отправить местоположение', 'request_location' => true]
                ]
            ],
            'resize_keyboard' => true
        ];
    }
}
