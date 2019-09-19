<?php
/**
 * Created by PhpStorm.
 * User: qissen
 * Date: 2019/3/22
 * Time: 21:51
 */

namespace app\core\middleware;
use app\core\exception\AppException;
use app\core\facade\AuthAppClient;
use think\facade\Request;

class AppClient
{
    public function handle($request, \Closure $next)
    {
        $result = AuthAppClient::check($request);
        if($result->code() != $result::OK) {
            return $result->json();
        }

        $result = AuthAppClient::sign($request);
        if($result->code() != $result::OK) {
            return $result->json();
        }

        return $next($request);
    }
}