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

namespace app\workerman\command;

use app\core\Run;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Config;
use Workerman\Lib\Timer;
use Workerman\Worker as HttpWorker;
/**
 * Worker 命令行类
 */
class WorkerDev extends Command
{
    protected $config = [];
    public $lastMtime;

    public function configure()
    {
        $this->setName('worker')
            ->addArgument('action', Argument::OPTIONAL, "start|stop|restart|reload|status|connections", 'start')
            ->addOption('host', 'H', Option::VALUE_OPTIONAL, 'the host of workerman server.', null)
            ->addOption('port', 'p', Option::VALUE_OPTIONAL, 'the port of workerman server.', null)
            ->addOption('daemon', 'd', Option::VALUE_NONE, 'Run the workerman server in daemon mode.')
            ->setDescription('Workerman HTTP Server for ThinkPHP');
    }

    public function execute(Input $input, Output $output)
    {
        $action = $input->getArgument('action');

        if (DIRECTORY_SEPARATOR !== '\\') {
            if (!in_array($action, ['start', 'stop', 'reload', 'restart', 'status', 'connections'])) {
                $output->writeln("<error>Invalid argument action:{$action}, Expected start|stop|restart|reload|status|connections .</error>");
                return false;
            }

            global $argv;
            array_shift($argv);
            array_shift($argv);
            array_unshift($argv, 'think', $action);
        } elseif ('start' != $action) {
            $output->writeln("<error>Not Support action:{$action} on Windows.</error>");
            return false;
        }

        if ('start' == $action) {
            $output->writeln('Starting Workerman http server...');
        }

        //第一步，加载配置文件
        $this->config = require_once dirname(dirname(__FILE__)).'/config/worker.php';

        //第二步，实例化worker
        $worker = new HttpWorker('http://' . $this->config['host'] . ':' . $this->config['port']);
        $worker->count = $this->config['count'];
        $worker->name = $this->config['name'];

        //第三步，配置worker静态参数
        HttpWorker::$pidFile = $this->config['pidFile'].'_' . $this->config['port'];
        HttpWorker::$logFile = $this->config['logFile'];
        HttpWorker::$stdoutFile = $this->config['stdoutFile'];
        HttpWorker::$daemonize = $input->hasOption('d');



        //第四步，onWorkerStart
        $worker->onWorkerStart = function($worker)
        {
            $this->lastMtime = time();
            $this->timer($worker);
            ob_start();
            Run::init($worker);
        };

        //第四步，onConnect
        $worker->onConnect = function($connection)
        {

        };

        //第五步，onMessage
        $worker->onMessage = function($connection)
        {
            ob_clean();
            $connection->send(Run::server($connection).ob_get_contents());
        };
        //unset($this->config);
        HttpWorker::runAll();
    }


    public function timer($worker) {
        if (0 === $worker->id && $this->config['file_monitor']) {
            $paths = $this->config['file_monitor_path'];
            Timer::add($this->config['file_monitor_interval'], function () use ($paths) {
                foreach ($paths as $path) {
                    $dir      = new RecursiveDirectoryIterator($path);
                    $iterator = new RecursiveIteratorIterator($dir);

                    foreach ($iterator as $file) {
                        if (pathinfo($file, PATHINFO_EXTENSION) != 'php') {
                            continue;
                        }

                        if ($this->lastMtime < $file->getMTime()) {
                            echo '[update]' . $file . "\n";
                            posix_kill(posix_getppid(), SIGUSR1);
                            //echo posix_getppid();

                            $this->lastMtime = $file->getMTime();
                            return;
                        }
                    }
                }
            });
        }
    }
}



