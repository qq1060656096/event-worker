<?php
namespace Zwei\EventWork;

use Zwei\EventWork\Config\CronConfig;

/**
 * 计划任务
 * Class Cron
 * @package Zwei\EventWork
 */
class Cron extends Base
{
    /**
     * 运行计划任务
     * @param string $cronName cron名
     * @return mixed
     */
    public static final function run($cronName)
    {
        $config = new CronConfig();
        $class = $config->getCronClass($cronName);
        $callbackFunc = $config->getCronCallbackFunc($cronName);
        // 处理cron
        $obj    = new $class();
        return call_user_func_array([$obj, $callbackFunc], [$class]);
    }
}