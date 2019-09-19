<?php


namespace app\core\utils;


class Tool
{
    public static function createRandom($length = 6) {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 61)};    //生成php随机数
        }
        return $key;
    }

    public static function array_sort($data, $string) {
        $strings = array_column($data, $string);
        array_multisort($strings,SORT_DESC, $data);
        return $data;
    }

    public static function ejson($data) {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function djson($data, $type =  false) {
        return json_decode($data, $type);
    }

    public static function generate_code($length = 4) {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
    }

    public static function unique_order_create() {
        return uuid_create();
    }

    public static function unique_uuid_create($length = 4) {
        return uuid_create();
    }
}