<?php


namespace app\core\log;


use think\facade\Log;

class Report
{
    const DEBUG = true;
    const WARN = 'warn';
    const INFO = 'info';
    const ERROR = 'error';
    public static function output($value, $type = self::ERROR) {
        if(self::DEBUG) {
            Log::write('<------------------ begin ------------------->', $type);
            Log::write($value, $type);
            Log::write('<------------------ end ------------------->', $type);
        }
    }
}