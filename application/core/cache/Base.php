<?php


namespace app\core\cache;

use app\core\Current;
use app\core\exception\AppException;
use app\core\Module;
use function GuzzleHttp\Promise\inspect;
use Redis;

class Base
{
    private $module;
    protected $keyPre;
    private static $redis;
    public function __construct()
    {
        $this->module = Current::get();
        $this->keyPre = $this->module->getGroup().'/'.$this->module->getModule();
    }

    public function cache() {
        if(empty(self::$redis)) {
            self::$redis = new Redis();
        }

        $config1 = $this->module->getConfig('cache');
        if(!isset($config1['serviceIcon']) || !$config1['serviceIcon']) {
            throw new AppException(0, 'config error，'.Current::get()->getConfigFullName('cache/{"serviceIcon":""}'));
        }
        $config2 = $this->module->getService_Cache($this->module->getModule().'/'.$config1['serviceIcon']);
        if(!isset($config1['timeout']) || !$config1['timeout']) {
            throw new AppException(0, 'config error，'.Current::get()->getConfigFullName('cache/{"timeout":""}'));
        }
        self::$redis->pconnect($config2->ServiceAddress, $config2->ServicePort, $config1['timeout']);
        self::$redis->_prefix($this->keyPre);
        return self::$redis;
    }
}