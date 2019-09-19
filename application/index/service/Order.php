<?php

/**
 * Created by PhpStorm.
 * User: qissen
 * Date: 2019/3/22
 * Time: 22:11
 */

namespace app\index\service;


class Order
{
    public function check($uuid, $productID) {
        $checkTime = time();
        $orderNumber = mt_rand(0, 99999);
        return '{"code":1,"msg":"操作成功","content":{"orderNumber":"'.$orderNumber.'","uuid":"'.$uuid.'","checkTime":"'.$checkTime.'"}}';
    }

    /**
     * @param $uuids - 用户ID列表
     * @param $productID - 产品ID
     * @param $projectID - 项目ID
     */
    public function getStatuss($uuids, $productID, $projectID) {
        return [];
    }

    public function pay() {
        return 'changePassword';
    }
}