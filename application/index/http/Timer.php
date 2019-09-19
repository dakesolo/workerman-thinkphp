<?php

/**
 * Created by PhpStorm.
 * User: qissen
 * Date: 2019/3/22
 * Time: 22:11
 */

namespace app\index\http;

use app\index\service\Order;

class Timer extends Base
{
    /**
     * 更新book表方法
     *
     * 及时更新book表
     * 每隔一秒钟轮询一次，过程阻塞，如果要求购买量较大可以采用hScan
     */
    public function intervalBook() {
        $order = new Order();
        $keys = $this->redis->hKeys($this->createKey('book'));
        $statuss = $order->getStatuss($keys, $this->config['product'], $this->config['project']);
        foreach ($statuss as $k=>$status) {
            //过期了
            if($status == 3) {
                //删除这个用户
                $this->redis->hDel($this->createKey('book'), $keys[$k]);
            }

            //已支付
            if($status == 2) {
                $this->redis->hSet($this->createKey('book'), $keys[$k], 2);
            }

            //未支付
            if($status == 1) {
                //啥也不干
            }
        }
    }

    /**
     * 关闭交易
     *
     * 及时查看支付数量，如果达到预期，则关闭交易
     * 每隔一秒钟轮询一次，过程阻塞，如果要求购买量较大可以采用hScan
     */
    public function intervalOver() {
        $list = $this->redis->hGetAll($this->createKey('book'));
        if(count($list) < $this->config['limit']) {
            return;
        }
        foreach ($list as $k=>$status) {
            if($status == 1) {
                return;
            }
        }
        //设置over
        $this->setOver();
    }
}