<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class TelegramUserController extends Controller
{
    public function testaction()
    {
        return response()->json([
           'success' => true
        ]);
    }
}
