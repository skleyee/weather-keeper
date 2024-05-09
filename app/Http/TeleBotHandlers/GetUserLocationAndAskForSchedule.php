<?php

namespace App\Http\TeleBotHandlers;

use App\Services\Weather\WeatherService;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Handlers\CommandHandler;
use WeStacks\TeleBot\Objects\Message;

class GetUserLocationAndAskForSchedule extends CommandHandler
{
    use WorksWIthTgUser;

    public function trigger(): bool
    {
        return isset($this->update->message) && $this->isThisLocationMessage() && ! isset($this->update->chat_join_request);
    }

    protected function isThisLocationMessage(): bool
    {
        return isset($this->update->message->location);
    }

    public function handle()
    {
        $user = $this->firstOrCreateTgUser($this->update->message->chat->id);
        $location = $this->update->message->location;
        if ($city = $this->getCityByCoordinates($location)) {
            $user->update([
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'city' => $city
            ]);
            return $this->answerWithButton("Нашёл! Вы живёте в городе $user->city. Выберите, когда вы хотите получать информацию о погоде", $this->getButtonWithSchedule());
        } else {
            return $this->answerWithButton('Возникла какая-то проблема при поиске города. Пожалуйста, попробуйте отправить своё местоположение повторно', $this->getLocationButton());
        }
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

    protected function getButtonWithSchedule()
    {
        return [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Каждый день утром',
                        'callback_data' => 'every_morning'
                    ]
                ],
                [
                    [
                        'text' => 'Каждый день днём',
                        'callback_data' => 'every_noon'
                    ]
                ],
                [
                    [
                        'text' => 'Каждый день вечером',
                        'callback_data' => 'every_evening'
                    ]
                ],
                [
                    [
                        'text' => 'В воскресенье на неделю вперёд',
                        'callback_data' => 'every_sunday'
                    ]
                ],
            ],
            'one_time_keyboard' => true,
            'resize_keyboard' => true
        ];
    }

    protected function getCityByCoordinates(object $coordinates)
    {
        $weatherService = app()->make(WeatherService::class);
        return $weatherService->getCityByCoordinates($coordinates);
    }
}
