<?php
namespace Wei\EventWork;

/**
 * 基类
 * Class Base
 * @package Wei\EventWork
 */
class Base
{
    /**
     * 获取当前IP地址
     * @return string
     */
    public static function getIP()
    {
        $ip = empty($_SERVER['REMOTE_ADDR']) ? '0.0.0.0': $_SERVER['REMOTE_ADDR'];
        return $ip;
    }

}