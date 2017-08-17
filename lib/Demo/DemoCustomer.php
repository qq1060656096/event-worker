<?php
namespace Wei\EventWork\Demo;

/**
 * demo消费者
 * Class DemoCustomer
 * @package Wei\EventWork
 */
class DemoCustomer
{
    /**
     * 消费者调用方法
     *
     * @param string $evenKey 事件key
     * @param mixed $data 数据
     * @param array $eventRaw 事件原始数据
     * @return bool true 事件执行成功,否则执行失败
     */
    public function callMethod($evenKey, $data, $eventRaw)
    {
        print_r($evenKey);
        print_r($data);
        print_r($eventRaw);
        return true;
    }
}