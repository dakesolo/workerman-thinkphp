<?php
/**
 * Created by PhpStorm.
 * User: qissen
 * Date: 2019/3/23
 * Time: 18:02
 */

namespace app\core\facade;

use app\core\entity\Result;
use think\Facade;
use think\facade\Request;

/**
 * @see \Redis
 * @mixin \Redis
 * @method void connect(String $ip, int $port) static 连接
 * @method Result sign(Request $request) static 检测签名
 * @method void send() static 发送数据到客户端
 */
class Redis extends Facade
{
    /**
     *
     * @return string
     */
    protected static function getFacadeClass()
    {
        return \Redis::class;
    }
}