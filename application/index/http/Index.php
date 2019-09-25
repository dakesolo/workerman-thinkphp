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
}