<?php
return [
    'worker'=> config('app_debug') ? \app\workerman\command\WorkerDev::class : \app\workerman\command\Worker::class
];
