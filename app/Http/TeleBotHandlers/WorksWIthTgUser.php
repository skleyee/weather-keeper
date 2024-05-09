<?php

namespace App\Http\TeleBotHandlers;

use App\Models\TelegramUser;

trait WorksWIthTgUser
{
    public function firstOrCreateTgUser($tgId): TelegramUser
    {
        $existingUser = TelegramUser::query()->firstWhere('tg_user_id', $tgId);
        if ($existingUser) {
            return $existingUser;
        }

        return TelegramUser::query()->create([
            'tg_user_id' => $tgId
        ]);
    }
}
