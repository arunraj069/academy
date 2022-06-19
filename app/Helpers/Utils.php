<?php

namespace App\Helpers;
use Carbon\Carbon;

class Utils
{
	public static function dateTimeFormat($datetime,$format='d-m-Y H:i:s')
    {
        return (new Carbon($datetime))->format($format);
    }
    public static function random($length, $chars = '')
    {
        if (!$chars) {
            $chars = implode(range('a','f'));
            $chars .= implode(range('0','9'));
        }
        $shuffled = str_shuffle($chars);
        return substr($shuffled, 0, $length);
    }
}