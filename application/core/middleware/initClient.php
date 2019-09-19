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

class initClient
{
    public function handle($request, \Closure $next)
    {
        return $next($request);
    }
}