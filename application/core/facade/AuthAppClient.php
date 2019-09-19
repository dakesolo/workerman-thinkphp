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
 * @see \app\core\auth\AppClient
 * @mixin \app\core\auth\AppClient
 * @method Result check(Request $request) static 检测公共字段
 * @method Result sign(Request $request) static 检测签名
 * @method void send() static 发送数据到客户端
 */
class AuthAppClient extends Facade
{
    /**
     *
     * @return string
     */
    protected static function getFacadeClass()
    {
        return \app\core\auth\AppClient::class;
    }
}