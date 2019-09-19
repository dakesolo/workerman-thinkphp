<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/3/21
 * Time: 13:39
 */

namespace app\core\entity;


use app\core\utils\Tool;
use Workerman\Protocols\Http as WorkerHttp;

class Result
{
    const OK = 1;
    /**
     *
     * @var int
     */
    private $code = 1;

    /**
     *
     * @var string
     */
    private $msg = '操作成功';

    /**
     *
     * @var string
     */
    private $content;

    /**
     *
     * @var \Exception
     */
    private $e;

    /**
     * json
     *
     * @var mixed
     */
    private $data;

    public function __toString()
    {
        return "{$this->code}-{$this->msg}-{$this->content}";
    }

    /**
     *
     * @param number $code
     * @param boolean $rr
     * @return number|Result
     */
    public function getThis($code = null, $rr = false)
    {
        return $this;
    }


    /**
     *
     * @param number $code
     * @param boolean $rr
     * @return number|Result
     */
    public function code($code = null, $rr = false)
    {
        if (isset($code) || $rr) {
            $this->code = $code;
            return $this;
        }
        return $this->code;
    }

    /**
     *
     * @param string $msg
     * @param boolean $rr
     *            force to return $this
     * @return string|Result
     */
    public function msg($msg = null, $rr = false)
    {
        if (isset($msg) || $rr) {
            $this->msg = $msg;
            return $this;
        }
        return $this->msg;
    }

    /**
     *
     * @param string $content
     * @param boolean $rr
     *            force to return $this
     * @return string|Result
     */
    public function content($content = null, $rr = false)
    {
        if (isset($content) || $rr) {
            $this->content = $content;
            return $this;
        }
        return $this->content;
    }


    /**
     *
     * @return array
     */
    public function data()
    {
        $result = [];
        $result['code'] = $this->code;
        $result['msg'] = $this->msg;
        if($this->content) {
            $result['content'] = $this->content;
        }
        return $result;
    }

    /**
     *
     * @return object
     */
    public function object()
    {
        $result = new \stdClass();
        $result->code = $this->code;
        $result->msg = $this->msg;
        if($this->content) {
            $result->content = $this->content;
        }
        return $result;
    }

    /**
     *
     * @param string $json
     * @param boolean $rr
     * @return string
     */
    public function json($json = null, $rr = false)
    {
        WorkerHttp::header('Content-Type:application/json; charset=utf-8');
        if (isset($json) || $rr) {
            //如果是json rpc服务返回的结果，如果该次访问是http接口，那么需要进行头部json处理；当然如果作为服务输出，则该操作虽然执行，但是结果还是按照json rpc无头部处理。
            return $json;
        }
        return Tool::ejson($this->data());
    }
}