<?php

namespace App\Console\Commands;

use App\Enums\Schedule;
use App\Models\TelegramUser;
use App\Services\TeleBot\TeleBotService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class sendRegularWeatherInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:weather-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sends weather to users';

    protected const SUNDAYS_TIMINGS = [
        '10:00',
        '15:00',
        '18:00'
    ];

    /**
     * Execute the console command.
     */
    public function handle(TeleBotService $teleBotService)
    {
        $tgUsers = TelegramUser::query()->with('consoleCommandSchedule')->get();
        $currentTime = Carbon::now();
        $currentTime = $currentTime->setTime($currentTime->hour, 0, 0);
        $tgUsers->each(function ($tgUser) use ($currentTime, $teleBotService) {
            $userSchedule = $tgUser->consoleCommandSchedule;
            if (!$userSchedule->exists()) {
                logger('There is no schedule for the user. Breaking script');
                die;
            }
            if ($this->checkIfItIsEveryDaySchedule($userSchedule->schedule)) {
                if ($currentTime == Carbon::createFromTimeString($userSchedule->push_time)) {
                    return $teleBotService->getMessageWithWeatherForUser($tgUser);
                }
            }

            if ($this->checkIfItIsSundaySchedule($userSchedule->schedule)) {
                if ($this->checkIfTodayIsSunday() && $this->isItTimeToSendSundayMessage($currentTime)) {
                    return $teleBotService->getMessageWithWeatherForUser($tgUser);
                }
            }
        });
        return Command::SUCCESS;

    }

    private function checkIfItIsEveryDaySchedule(string $usersSchedule): bool
    {
       return in_array($usersSchedule, Schedule::getEveryDayValues());
    }

    private function checkIfItIsSundaySchedule(string $usersSchedule): bool
    {
        return $usersSchedule === Schedule::EverySunday->value;
    }

    private function checkIfTodayIsSunday(): bool
    {
        return Carbon::now()->isSunday();
    }

    private function isItTimeToSendSundayMessage($currentTime): bool
    {
        foreach (self::SUNDAYS_TIMINGS as $sundayTiming) {
            if (Carbon::createFromTimeString($sundayTiming) == $currentTime) {
                return true;
            }
        }
        return false;
    }
}
