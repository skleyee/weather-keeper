<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('send:weather-info')->everyTenSeconds();
