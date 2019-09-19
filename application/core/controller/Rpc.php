<?php


namespace app\core\controller;


use app\core\Current as Core_Current;
use app\core\entity\Result as Core_Result;
use app\core\Request as Core_Request;
use rpc\RpcClient as Core_RpcClient;

class Rpc
{
    protected $result;
    protected $data;
    protected $common;
    protected $request;
    protected $module;
    protected $current;

    public function __construct(Core_Request $request, $param = [])
    {
        $this->result = new Core_Result();
        $this->request = $request;
        $this->current = Core_Current::get();
        $this->common = $param[0];
        $this->data = $param[1];
        $this->initialize();
    }

    public function initialize() {}

    public function call($service, $controller, $method, $data, $type = '') {
        $service = $this->current->getService_RPC($service);
        $common = [
            'secret'=>$service->secret,
            'access'=>$this->request->getAccess()->data()
        ];
        Core_RpcClient::config([
            $service->host
        ]);
        $client = Core_RpcClient::instance($controller);
        if(!$type) {
            return $client->$method($common, $data);
        }
        $method = $type.$method;
        $client->$method($common, $data);
        return true;
    }
}