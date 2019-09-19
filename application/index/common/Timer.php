<?php

/**
 * Created by PhpStorm.
 * User: qissen
 * Date: 2019/3/22
 * Time: 22:11
 */

namespace app\index\common;

use app\core\Timer as Core_Timer;
use Workerman\Lib\Timer as WorkerTimer;

class Timer extends Core_Timer
{

    public function add() {
        if($this->worker->id === 0) {
            WorkerTimer::add(1, [new \app\index\http\Timer(), 'intervalBook'], [], true);
        }
    }
}