<?php

use App\Http\Controllers\Api\TelegramController;
use Illuminate\Support\Facades\Route;

Route::get('/webform', [TelegramController::class, 'webform']);