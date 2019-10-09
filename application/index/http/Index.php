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
use think\Db;
use Workerman\Connection\AsyncTcpConnection;

class Index extends Http
{
    public function index() {
        Redis::action()->set('x', 'asdf');
        return Redis::action()->get('x');
    }

    public function db() {
        dump(Db::name('user')->where('userID',1)->select());
        return '';
    }

    public function getUser() {
        // 不支持直接指定http，但是可以用tcp模拟http协议发送数据
        $connection_to_baidu = new AsyncTcpConnection('tcp://www.baidu.com:80');
        // 当连接建立成功时，发送http请求数据
        $connection_to_baidu->onConnect = function($connection_to_baidu)
        {
            echo "connect success\n";
            $connection_to_baidu->send("GET / HTTP/1.1\r\nHost: www.baidu.com\r\nConnection: keep-alive\r\n\r\n");
        };
        $connection_to_baidu->onMessage = function($connection_to_baidu, $http_buffer)
        {
            echo $http_buffer;
        };
        $connection_to_baidu->onClose = function($connection_to_baidu)
        {
            echo "connection closed\n";
        };
        $connection_to_baidu->onError = function($connection_to_baidu, $code, $msg)
        {
            echo "Error code:$code msg:$msg\n";
        };
        $connection_to_baidu->connect();
        //return '1';
    }
}