<?php

/**
 * Created by PhpStorm.
 * User: qissen
 * Date: 2019/3/22
 * Time: 22:11
 */

namespace app\index\http;

use app\core\controller\Http;

class Index extends Http
{
    public function index() {
        return 'hello world';
    }
}