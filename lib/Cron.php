<?php
namespace Wei\EventWork;


/**
 * 计划任务
 *
 * Class Cron
 * @package Wei\EventWork
 */
class Cron extends Base
{
    /**
     * 运行计划任务
     * @param string $cronName cron名
     */
    public static final function run($cronName)
    {

        $class  = EventConfig::getCronClass($cronName);
        // 处理cron
        $obj    = new $class();
        call_user_func_array([$obj, 'run'], [$cronName]);
    }
}


