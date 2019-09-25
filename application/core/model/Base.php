<?php


namespace app\core\model;

use app\core\Current;
use think\Db;
use think\Model;

class Base extends Model
{
    // 模型初始化
    protected function initialize()
    {
        //TODO:初始化内容
        /*$config1 = Current::get()->getConfig('database');
        $config2 = Current::get()->getService_DB(Current::get()->getModule().'/'.$config1['serviceIcon']);
        $config = [
            // 数据库类型
            'type'            => 'mysql',
            // 服务器地址
            'hostname'        => $config2->ServiceAddress,
            // 数据库名
            'database'        => $config1['dbname'],
            // 用户名
            'username'        => $config1['username'],
            // 密码
            'password'        => $config1['password'],
            // 端口
            'hostport'        => $config2->ServicePort,
            // 连接dsn
            'dsn'             => '',
            // 自动写入时间戳字段
            // 数据库调试模式
            'debug'           => false,
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'          => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'     => false,
            // 读写分离后 主服务器数量
            'master_num'      => 1,
            // 指定从服务器序号
            'slave_no'        => '',
            // 自动读取主库数据
            'read_master'     => false,
            // 是否严格检查字段是否存在
            'fields_strict'   => true,
            // 数据集返回类型
            'resultset_type'  => 'array',
            // 自动写入时间戳字段
            'auto_timestamp'  => false,
            // 时间字段取出后的默认时间格式
            'datetime_format' => 'Y-m-d H:i:s',
            // 是否需要进行SQL性能分析
            'sql_explain'     => false,
            // Builder类
            'builder'         => '',
            // Query类
            'query'           => '\\think\\db\\Query',
            // 数据库连接参数
            'params'          => [
                \PDO::ATTR_PERSISTENT   => true,
            ],
            // 数据库编码默认采用utf8
            'charset'         => 'utf8',
            'prefix'=>'a_',
            // 是否需要断线重连
            'break_reconnect' => true,
        ];*/
        //Db::init($config);
    }
}