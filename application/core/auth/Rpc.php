<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/3/22
 * Time: 10:41
 */

namespace app\core\auth;
use app\core\Module;
use app\core\Request;

class Rpc
{
    private $current;

    public function __construct(Module $current)
    {
        $this->current = $current;
    }

    public function check($secret) {
        $_secret = $this->getSecret();
        if(!$_secret) {
            return false;
        }
        if($_secret != $secret) {
            return false;
        }
        return true;
    }

    public function getSecret() {
        $services = $this->current->pullService($this->current->getModule().'/rpc');
        foreach ($services as $item) {
            if($item->ServiceAddress == Request::getLocalIp()) {
                return $item->ServiceMeta->secret;
            }
        }
        return null;
    }
}