<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TelegramUser extends Model
{
    use HasFactory;

    protected $table = 'tg_users';

    protected $fillable = [
        'tg_user_id',
        'city',
        'latitude',
        'longitude'
    ];

    public function consoleCommandSchedule(): HasOne
    {
        return $this->hasOne(ConsoleCommandSchedule::class, 'tg_user_id', 'tg_user_id');
    }
}
