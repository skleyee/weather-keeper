<?php

use App\Http\Controllers\Api\TelegramUserController;
use Illuminate\Support\Facades\Route;

Route::get('xdd', [TelegramUserController::class, 'xdd']);
