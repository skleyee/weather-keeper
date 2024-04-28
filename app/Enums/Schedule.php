<?php

namespace App\Enums;

enum Schedule: string
{
    case EveryMorning = 'every_morning';
    case EveryNoon = 'every_noon';
    case EveryEvening = 'every_evening';
    case EverySunday = 'every_sunday';

}
