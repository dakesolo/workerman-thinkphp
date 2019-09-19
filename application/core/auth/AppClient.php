<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/3/22
 * Time: 10:41
 */

namespace app\core\auth;

use app\core\constant\Code;
use app\core\entity\Result;
use app\core\exception\AppException;
use app\core\model\Client;
use think\facade\Config;
use think\facade\Request;
use think\facade\Validate;

class AppClient
{
    public function check($request):Result {
        $result = new Result();
        $rule = [
            'sign'  => 'require',
            'token'   => 'require',
            'osType' => 'require',
            'appType' => 'require',
            'deviceID' => 'require',
            'appV' => 'require',
            'channel' => 'require',
            'time' => 'require|number|length:10',
            'nonce' => 'require',
        ];

        $validate = Validate::make($rule);
        if (!$validate->check($request->get())) {
            return $result->code(Code::ERROR_CHECK)->msg($validate->getError());
        }
        return $result;
    }

    public function sign($request) {
        $result = new Result();
        $common = $request->get();
        $data = $request->getInput();
        $sign = sha1(Config::get('auth.appKey.appClient').$this->getAppSecret($request).$common['token'].$common['osType'].$common['appType'].$common['deviceID'].$common['appV'].$common['channel'].$common['time'].$common['nonce'].$data);
        if($sign != $common['sign']) {
            return $result->code(Code::ERROR_SIGN)->msg('error sign');
        }
        return $result;
    }

    /**
     * @param string $deviceID
     * @return string
     */
    public static function createAppSecret($deviceID = '') {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        $key = '';
        for ($i = 0; $i < 32; $i++) {
            $key .= $pattern{mt_rand(0, 61)};    //生成php随机数
        }
        $appSecret = sha1($deviceID.microtime().$key);
        return $appSecret;
    }

    /**
     * @param Request $request
     * @return string
     * @throws
     */
    private function getAppSecret($request) {
        $common = $request->get();
        $api = strtolower(request()->controller()).'/'.request()->action();
        $login = Config::get('auth.auth_login');
        //dump($request->action());
        //dump($request);
        if(!$login || !in_array($api, $login)) {
            return '';
        }
        $client = Client::withSearch(['tokenExpore'])
            ->where('token', $common['token'])
            ->where('deviceID', $common['deviceID'])->find();
        if(!$client) {
            throw new AppException(CODE::ERROR_LOGIN, 'need login');
        }
        return $client['appSecret'];
    }
}