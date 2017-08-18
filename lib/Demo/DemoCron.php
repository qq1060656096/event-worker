<?php
namespace Wei\EventWork\Demo;

use Wei\EventWork\CronInterface;

/**
 * 测试计划任务
 *
 * Class DemoCron
 * @package Wei\EventWork\Demo
 */
class DemoCron implements CronInterface
{

    /**
     * 运行计划任务
     * @param string $cronName 计划任务名字
     */
    public function run($cronName)
    {
        echo "\nstart cron : $cronName\n";
        for ($i = 1; $i < 11; $i++) {
            echo "$i\n";
            usleep(500000);
        }
        echo "\nend cron : $cronName\n";
    }
}