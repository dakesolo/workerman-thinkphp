<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think\worker;

use think\App;
use think\Error;
use think\exception\HttpException;
use Workerman\Protocols\Http as WorkerHttp;

/**
 * Worker应用对象
 */
class ApplicationRpc extends App
{
    /**
     * 处理Worker请求
     * @access public
     * @param  \Workerman\Connection\TcpConnection   $connection
     * @param  void
     */
    public function worker($connection, $data)
    {
        try {
            ob_start();
            // 重置应用的开始时间和内存占用
            $this->beginTime = microtime(true);
            $this->beginMem  = memory_get_usage();

            // 销毁当前请求对象实例
            $this->delete('think\Request');
            $pathinfo = $data['class'].'/'.$data['method'];

            // 更新请求对象实例
            $this->request->setPathinfo($pathinfo);
            $this->route->setRequest($this->request);
            $response = $this->run();
            $response->send();

            $content = ob_get_clean();
            $connection->send($content);
        } catch (HttpException $e) {
            $this->exception($connection, $e);
        } catch (\Exception $e) {
            $this->exception($connection, $e);
        } catch (\Throwable $e) {
            $connection->send(json_encode([
                'code'=>-99999,
                'msg'=>$e->getMessage()
            ]));
        }

    }



    protected function exception($connection, $e)
    {
        //这里必须要自己定制错误
        $handler = Error::getExceptionHandler();
        $handler->report($e);
        $resp    = $handler->render($e);
        $content = $resp->getContent();
        $connection->send($content);
    }

}
