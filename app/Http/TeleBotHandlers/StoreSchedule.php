<?php

namespace App\Http\TeleBotHandlers;

use App\Enums\Schedule;
use App\Models\ConsoleCommandSchedule;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Handlers\CommandHandler;
use WeStacks\TeleBot\Objects\Message;

class StoreSchedule extends CommandHandler
{
    use WorksWIthTgUser;

    public function trigger(): bool
    {
        return isset($this->update->callback_query) && ! isset($this->update->chat_join_request);
    }

    public function handle()
    {
        $user = $this->firstOrCreateTgUser($this->update->callback_query->from->id);
        $schedule = $this->update->callback_query->data;

        $responseForSchedule = match ($this->update->callback_query->data) {
          Schedule::EveryMorning->value =>  $this->answerWithButton('В какое время вы хотите получать прогноз?', $this->getButtonWithMorningTime()),
          Schedule::EveryNoon->value =>  $this->answerWithButton('В какое время вы хотите получать прогноз?', $this->getButtonWithNoonTime()),
          Schedule::EveryEvening->value =>  $this->answerWithButton('В какое время вы хотите получать прогноз?', $this->getButtonWithEveningTime()),
          Schedule::EverySunday->value =>  $this->answerWithButton('В какое время вы хотите получать прогноз?', $this->getButtonWithSundayTime()),
          default => null
        };
        if ($responseForSchedule) {
            ConsoleCommandSchedule::storeCommandScheduleForUser($user->tg_user_id, $schedule);
            return $responseForSchedule;
        }
    }

    protected function answerWithButton(string $text, array $button): Message|PromiseInterface
    {
        return $this->sendMessage([
            'text' => $text,
            'reply_markup' => $button
        ]);
    }

    protected function getButtonWithMorningTime()
    {
        return [
            'inline_keyboard' => [
                [
                    [
                        'text' => '07:00',
                        'callback_data' => '07:00'
                    ]
                ],
                [
                    [
                        'text' => '08:00',
                        'callback_data' => '08:00'
                    ]
                ],
                [
                    [
                        'text' => '09:00',
                        'callback_data' => '09:00'
                    ]
                ],
                [
                    [
                        'text' => '10:00',
                        'callback_data' => '10:00'
                    ]
                ],
            ],
            'one_time_keyboard' => true,
            'resize_keyboard' => true
        ];
    }

    protected function getButtonWithNoonTime()
    {
        return [
            'inline_keyboard' => [
                [
                    [
                        'text' => '12:00',
                        'callback_data' => '12:00'
                    ]
                ],
                [
                    [
                        'text' => '13:00',
                        'callback_data' => '13:00'
                    ]
                ],
                [
                    [
                        'text' => '14:00',
                        'callback_data' => '14:00'
                    ]
                ],
            ],
            'one_time_keyboard' => true,
            'resize_keyboard' => true
        ];
    }

    protected function getButtonWithEveningTime()
    {
        return [
            'inline_keyboard' => [
                [
                    [
                        'text' => '18:00',
                        'callback_data' => '18:00'
                    ]
                ],
                [
                    [
                        'text' => '19:00',
                        'callback_data' => '19:00'
                    ]
                ],
                [
                    [
                        'text' => '20:00',
                        'callback_data' => '20:00'
                    ]
                ],
                [
                    [
                        'text' => '21:00',
                        'callback_data' => '21:00'
                    ]
                ],
            ],
            'one_time_keyboard' => true,
            'resize_keyboard' => true
        ];
    }

    protected function getButtonWithSundayTime()
    {
        return [
            'inline_keyboard' => [
                [
                    [
                        'text' => '10:00',
                        'callback_data' => '10:00'
                    ]
                ],
                [
                    [
                        'text' => '15:00',
                        'callback_data' => '15:00'
                    ]
                ],
                [
                    [
                        'text' => '18:00',
                        'callback_data' => '18:00'
                    ]
                ],
            ],
            'one_time_keyboard' => true,
            'resize_keyboard' => true
        ];
    }

}
