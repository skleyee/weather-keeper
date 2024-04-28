<?php

namespace App\Http\TeleBotHandlers;

use App\Enums\District;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Handlers\CommandHandler;
use WeStacks\TeleBot\Objects\Message;

class GetDistrictAndAskForSchedule extends CommandHandler
{
    use WorksWIthTgUser;

    public function trigger(): bool
    {
        return isset($this->update->message) && $this->isThisTextMessage() && ! isset($this->update->chat_join_request);
    }

    protected function isThisTextMessage(): bool
    {
        return isset($this->update->message->text);
    }

    public function handle()
    {
        $user = $this->firstOrCreateTgUser($this->update->message->chat->id);

        foreach (District::cases() as $district) {
            if ($this->update->message->text == District::getReadableOption($district)) {
                $user->update([
                    'district' => District::getReadableOption($district)
                ]);
                return $this->answerWithButton('Выбран ' . District::getReadableOption($district) . ' район. Когда вы хотите получать информацию о погоде?', $this->getButtonWithSchedule());
            }
        }
        $this->answerWithButton('Не понимаю:( Выберите, пожалуйста, район.', $this->getButtonWithDistricts());
    }

    protected function answerWithButton(string $text, array $button): Message|PromiseInterface
    {
        return $this->sendMessage([
            'text' => $text,
            'reply_markup' => $button
        ]);
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
