<?php

use App\Http\Controllers\Api\TelegramUserController;
use Illuminate\Support\Facades\Route;

Route::get('ping', [TelegramUserController::class, 'testaction']);
