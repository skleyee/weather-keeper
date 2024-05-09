<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsoleCommandSchedule extends Model
{
    use HasFactory;

    protected $table = 'console_command_schedule';

    protected $fillable = [
        'tg_user_id',
        'schedule',
        'push_time'
    ];

    public static function storeCommandScheduleForUser($tgUserId, $schedule)
    {
        return self::query()->updateOrCreate(
            [
            'tg_user_id' =>  $tgUserId,
        ],
            [
            'schedule'   =>  $schedule
        ]
        );
    }

    public static function storeCommandScheduleTime($tgUserId, $schedule, $time)
    {
        return self::query()->updateOrCreate(
            [
            'tg_user_id' =>  $tgUserId,
            'schedule'   =>  $schedule
        ],
            [
            'push_time' => $time
        ]
        );
    }
}
