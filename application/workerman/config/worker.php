<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Env;

// +----------------------------------------------------------------------
// | Workerman设置 仅对 php think worker 指令有效
// +----------------------------------------------------------------------
return [
    // 扩展自身需要的配置
    'host'                  => '0.0.0.0', // 监听地址
    //'host'                  => '127.0.0.1', // 监听地址
    'port'                  => 5001, // 监听端口
    'file_monitor'          => true, // 是否开启PHP文件更改监控（调试模式下自动开启）
    'file_monitor_interval' => 1, // 文件监控检测时间间隔（秒）
    'file_monitor_path'     => [Env::get('app_path')] , // 文件监控目录 默认监控application和config目录

    // 支持workerman的所有配置参数
    'name'                  => 'http',
    'count'                 => 4,
    'pidFile'               => Env::get('runtime_path') . 'worker.pid',
    'logFile'               => Env::get('runtime_path') . 'http_log.log',
    'stdoutFile'               => Env::get('runtime_path') . 'http_stdout.log'
];
