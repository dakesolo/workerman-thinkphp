<?php


namespace app\core;
use app\common\Timer;
use app\core\cache\Redis;
use think\Db;
use app\core\constant\Code;
use app\core\entity\Result;
use app\core\exception\AppException;
use think\facade\Config;
use Workerman\Worker;


class Run
{
    /**
     * 初始化数据库，redis，设置定时器等
     * @param Worker $worker
     */
    public static function init(Worker $worker) {
        //初始化redis
        Redis::init();

        //初始化数据库
        Db::init(Config::get('database.'));

        //初始化定时器
        Timer::init($worker);
    }

    /**
     * 每次用户访问，必执行一次
     * @param $connection
     * @return string
     */
    public static function server($connection) {

        $result = new Result();
        $send = $result->code(Code::ERROR_SYSTEM)->msg('未知错误')->json();
        try {
            //第一步，得到参数,$module,$controller,$action
            $pathinfo = ltrim(strpos($_SERVER['REQUEST_URI'], '?') ? strstr($_SERVER['REQUEST_URI'], '?', true) : $_SERVER['REQUEST_URI'], '/');

            $module =  Config::get('default_module');
            $controller = Config::get('default_controller');
            $action = Config::get('default_action');
            if($pathinfo != '') {
                $paths = explode('/', $pathinfo);
                $count = count($paths);
                if($count == 3) {
                    list($module, $controller, $action) = $paths;
                }
                if($count == 2) {
                    list($controller, $action) = $paths;
                    $module = 'index';
                }
                if($count == 1) {
                    $module = 'index';
                    $controller = 'Index';
                    $action = $pathinfo;
                }
            }
            //dump(Config::get('deny_module_list'));
            if(in_array($module, Config::get('deny_module_list'))) {
                throw new AppException(Code::ERROR_SYSTEM, 'deny_module');
            }



            //第二步，初始化一个module
            //Current::init($group, $module);
            //第四步，实例化controller，实行action
            $class  = '\app'.'\\'.$module.'\\'.'http'.'\\'.$controller;
            if($module == 'index') {
                $class = '\app'.'\\'.$module.'\\'.'http'.'\\'.$controller;
            }

            $request = new Request($connection);
            $request->setAction($action);
            $request->setController($controller);
            $request->setModule($module);

            $context = new $class($request);
            $send = $context->$action();
            if(!is_string($send)) {
                $send = $result->code(Code::ERROR_SYSTEM)->msg($class.'必须返回字符串')->json();
            }
            unset($context);
        } catch (AppException $e) {
            $send = $result->code($e->getCode())->msg($e->getMessage().' - '.$e->getFile().' - '.$e->getLine())->json();
        } catch (\Exception $e) {
            $send = $result->code(Code::ERROR_SYSTEM)->msg($e->getMessage().' - '.$e->getFile().' - '.$e->getLine())->json();
        } catch (\Throwable $e) {
            $send = $result->code(Code::ERROR_SYSTEM)->msg($e->getMessage().' - '.$e->getFile().' - '.$e->getLine())->json();
        }
        finally {
            return $send;
        }
    }
}