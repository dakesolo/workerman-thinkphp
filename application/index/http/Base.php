<?php

/**
 * Created by PhpStorm.
 * User: qissen
 * Date: 2019/3/22
 * Time: 22:11
 */

namespace app\index\http;

use app\core\cache\Redis;
use app\core\controller\Http;
use think\facade\Config;


class Base extends Http
{
    protected $config;
    protected $redis;

    public function initialize()
    {
        $this->config = Config::pull('index');
        $this->redis = Redis::action();
    }

    /**
     * 获取当前用户唯一身份
     * @return mixed|null
     */
    public function getUuid() {
        # return $this->request->getHeader('uuid');
        return 'dakesolo';
    }


    /**
     * 生成key
     * @return string
     */
    public function createKey($key) {
        return $key.'_'.$this->config['project'].'_'.$this->config['project'];
    }

    /**
     * 设置结束
     * @return string
     */
    public function setOver() {
        $this->redis->set($this->createKey('over'), 1);
    }
}