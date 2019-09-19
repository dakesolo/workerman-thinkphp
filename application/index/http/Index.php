<?php

/**
 * Created by PhpStorm.
 * User: qissen
 * Date: 2019/3/22
 * Time: 22:11
 */

namespace app\index\http;


use app\index\service\Order;



class Index extends Base
{
    public function index() {
        $value = $this->isOver();
        if($value) {
            return '结束了';
        }

        $value = $this->lock();
        if(!$value) {
            return '没抢到，继续抢';
        }

        $value = $this->book();
        if(!$value) {
            return '没抢到，继续抢';
        }

        $value = $this->checkOrder();
        if(!$value) {
            return '没抢到，继续抢';
        }

        return '抢到了';
    }

    /**
     * 活动是否结束
     * @return bool - 0 未结束，1结束了
     */
    public function isOver() {
        $over = $this->redis->get($this->createKey('over'));
        return $over;
    }

    /**
     * 并发锁
     * @return bool
     */
    public function lock() {
        $lock = $this->redis->set($this->createKey('lock'), 1, ['nx', 'ex' => 10]);
        if(!$lock) {
            return false;
        }
        return true;
    }

    /**
     * 删除锁
     * @return bool
     */
    public function delLock() {
        $this->redis->del($this->createKey('lock'));
    }

    /**
     * hash表
     * @return bool
     */
    public function book()
    {
        //超出数量
        $value = $this->redis->hLen($this->createKey('book'));
        if($value > $this->config['limit']) {
            $this->delLock();
            return false;
        }

        //用户重复
        $value = $this->redis->hExists($this->createKey('book'), $this->getUuid());
        if($value) {
            $this->delLock();
            return false;
        }

        //入表,未支付
        $value = $this->redis->hSet($this->createKey('book'), $this->getUuid(), 1);

        //解锁
        $this->delLock();
        if(!$value) {
            return false;
        }
        return true;
    }

    /**
     * 下单
     * @return bool
     */
    public function checkOrder()
    {
        $order = new Order();
        $result = $order->check($this->getUuid(), $this->config['product']);
        if(!$result) {
            $this->redis->hDel($this->createKey('book'), $this->getUuid());
            return false;
        }
        $this->redis->hSet($this->createKey('book'), $this->getUuid(), $result);
        return true;
    }

    /*public function watch() {
        dump($this->redis->hGetAll($this->createKey('book')));
        dump($this->redis->hLen($this->createKey('book')));
    }

    public function init() {
        return $this->redis->del($this->createKey('book'));
    }*/
}