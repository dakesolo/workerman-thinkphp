<?php


namespace app\core\controller;

use app\core\entity\Result as Core_Result;
use app\core\Request as Core_Request;


class Http
{
    protected $result;
    protected $current;
    protected $request;
    public function __construct(Core_Request $request = null)
    {
        $this->result = new Core_Result();
        $this->request = $request;
        $this->initialize();
    }

    public function initialize() {}


}