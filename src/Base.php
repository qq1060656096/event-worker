<?php
namespace Zwei\EventWork;

use Zwei\Base\DB;

/**
 * 基类
 * Class Base
 * @package Zwei\EventWork
 */
class Base
{

    /**
     * 获取事件日志表名
     * @return string
     */
    public function getEventLogTableName()
    {
        $tableName = DB::getInstance()->getTable('event_log');
        return $tableName;
    }

    /**
     * 获取事件模块日志表名
     * @return string
     */
    public function getEventModuleLogTableName()
    {
        $tableName = DB::getInstance()->getTable('event_module_log');
        return $tableName;
    }

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