<?php


namespace app\core\cache;

use think\facade\Config;

class Redis
{
    protected $keyPre;
    private static $redis;

    /**
     * 初始化
     */
    public static function init() {
        $config = Config::pull('cache');
        self::$redis = new \Redis();
        self::$redis->pconnect($config['host'], $config['port']);
        self::$redis->_prefix($config['host']);
    }

    /**
     * 使用
     */
    public static function action() {
        if(empty(self::$redis)) {
            self::init();
            return self::$redis;
        }
        return self::$redis;
    }
}