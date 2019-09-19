<?php


namespace app\core;

use Workerman\Worker;
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

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
}