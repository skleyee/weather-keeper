<?php

namespace App\Enums;

enum District: string
{
    case Soviet = 'soviet';
    case Bezhitsky = 'bezhitsky';
    case Volodarsky = 'volodarsky';
    case Fokinsky = 'fokinsky';


    public static function getReadableOption(self $district): string {
        return match ($district->value) {
            'soviet' => 'Советский',
            'bezhitsky' => 'Бежицкий',
            'volodarsky' => 'Володарский',
            'fokinsky'  => 'Фокинский'
        };
    }
}
