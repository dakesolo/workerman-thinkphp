<?php
/**
 * Created by PhpStorm.
 * User: zhangdadan
 * Date: 2017/6/6
 * Time: 23:09
 */


namespace app\core\exception;
use app\core\constant\Code;
use app\core\entity\Result;
use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\facade\Response;

class Http extends Handle
{
    public function render($e)
    {
        if ($e instanceof HttpException) {

            if(config('app_debug')) {
                //交由系统处理
                return parent::render($e);
            }
            $statusCode = $e->getStatusCode();
            $response = Response::create((new Result())->code(CODE::ERROR_SYSTEM)->msg('HttpException:'.$e->getMessage())->data(), 'json')->code($statusCode);
            return $response;
        }
        else if ($e instanceof AppException) {
            $statusCode = $e->getStatusCode();
            $response = Response::create((new Result())->code($statusCode)->msg($e->getMessage())->data(), 'json')->code(200);
            return $response;
        }
        else if ($e instanceof \Throwable) {
            //因为不存在Throwable的Handle，所以没法处理，只能直接返回了
            $response = Response::create((new Result())->code(CODE::ERROR_SYSTEM)->msg('Throwable:'.$e->getMessage())->data(), 'json')->code(500);
            return $response;
        }
        return parent::render($e);
    }
}