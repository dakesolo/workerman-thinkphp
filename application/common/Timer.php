<?php

/**
 * Created by PhpStorm.
 * User: qissen
 * Date: 2019/3/22
 * Time: 22:11
 */

namespace app\common;

use Workerman\Lib\Timer as WorkerTimer;

class Timer
{
    protected $worker;
    public static $timer;
    public function __construct($worker)
    {
        $this->worker = $worker;
    }

    public static function init($worker) {
        self::$timer = new self($worker);
        self::$timer->add();
    }

    /**
     * 增加定时器，该定时器与模块无关，与进程有关
     * 参考http://doc.workerman.net/timer/add.html，定时器的多种写法
     * 一般将定时器执行的函数或类方法写到各模块中，进程ID和执行频率通过该方法配置
     */
    public function add()
    {
        if($this->worker->id === 0) {
            WorkerTimer::add(1, function(){

            }, true);
        }
    }
}