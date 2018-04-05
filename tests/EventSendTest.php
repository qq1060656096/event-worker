<?php
namespace Zwei\EventWork\Tests;



/**
 * 测试事件
 *
 * Class EventSendTest
 * @package Zwei\EventWork\Tests
 */
class EventSendTest extends BaseTestCase
{
    public function test()
    {
        $data = [
            'productId' => 1,// 购买产品id
            'quantity' => 10,// 购买数量
            'couponId' => 0,// 优惠券id
            'uid' => 10,//购买用户
        ];
        // 用户购买产品
        \Zwei\EventWork\EventSend::send('BUY_PRODUCT', $data);
    }
}