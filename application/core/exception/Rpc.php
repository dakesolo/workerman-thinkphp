<?php
/**
 * Created by PhpStorm.
 * User: zhangdadan
 * Date: 2017/6/6
 * Time: 23:09
 */


namespace app\core\exception;

use app\core\entity\Result;

class Rpc
{
    public static function content($e)
    {
        return (new Result())->code($e->getCode())->msg($e->getMessage())->json();
    }
}