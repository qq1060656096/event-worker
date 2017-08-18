<?php
namespace Wei\EventWork;


/**
 * 计划任务接口
 *
 * Interface CronInterface
 * @package Wei\EventWork
 */
interface CronInterface
{
    /**
     * 运行计划任务
     * @param string $cronName 计划任务名字
     */
    public function run($cronName);

}


