<?php


namespace app\core;

use app\core\utils\Tool;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http;
use Workerman\Worker;


class Request
{
    private $connection;
    private $module;
    private $controller;
    private $action;
    public function __construct(TcpConnection $connection)
    {
        $this->connection = $connection;
    }

    public function get($name) {
        if(isset($_GET[$name])) {
            return $_GET[$name];
        }
        return null;
    }

    public function getPost() {
        $param = $_POST;
        if(empty($param)) {
            $param = file_get_contents("php://input");
            $param = !empty($param) ? $param : $GLOBALS['HTTP_RAW_POST_DATA'];
            if(!empty($param)) {
                $param = Tool::djson($param, true);
            }
        }
        return $param;
    }

    public function getDomain() {
        return $_SERVER['HTTP_X_FORWARDED_HOST'];
    }

    public function getHeader($key) {
        return isset($_SERVER['HTTP_'.strtoupper($key)]) ? $_SERVER['HTTP_'.strtoupper($key)] : null;
    }

    public function getLocalIp() {
        return $this->connection->getLocalIp();
    }

    public function getLocalPort() {
        return $this->connection->getLocalPort();
    }

    public function setHeader($value) {
        Http::header($value);
    }

    public function setController($value) {
        $this->controller = $value;
    }

    public function setModule($value) {
        $this->module = $value;
    }

    public function getModule() {
        return $this->module;
    }

    public function setAction($value) {
        $this->action = $value;
    }

    public function getController() {
        return $this->controller;
    }

    public function getAction() {
        return $this->action;
    }

}