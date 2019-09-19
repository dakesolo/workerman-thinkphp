<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/3/21
 * Time: 13:39
 */

namespace app\core\entity;

class Access
{
    private $sign;
    private $level;//level 从1开始
    private $signTime;
    const TYPE_ERROR = 'TYPE_ERROR';
    const TYPE_ACCESS = 'TYPE_ACCESS';
    const DIR_IN = 'DIR_IN';
    const DIR_OUT = 'DIR_OUT';

    public function forward() {
        if($this->level) {
            $this->level++;
        }
        else {
            $this->level = 1;
        }
    }

    function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->$name = $value;
    }

    function __get($name)
    {
        // TODO: Implement __get() method.
        if(!isset($this->$name)) {
            return null;
        }
        return $this->$name;
    }

    public function data() {
        return [
            'sign'=>$this->sign,
            'signTime'=>$this->signTime,
            'level'=>$this->level,
        ];
    }

}