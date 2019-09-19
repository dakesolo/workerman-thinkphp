<?php


namespace app\core\utils;


class Time
{
    public static function timestampToTime($value, $format = "Y-m-d H:i:s") {
        if($value) return date($format, $value);
        return '';
    }

    public static function timestampToDate($value, $format = "Y-m-d") {
        if($value) return date($format, $value);
        return '';
    }
}