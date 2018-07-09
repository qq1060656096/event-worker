<?php
namespace Zwei\EventWork\Tests;

use Zwei\EventWork\Cron;


/**
 * 测试计划任务
 *
 * Class CronTest
 * @package Zwei\EventWork\Tests
 */
class CronTest extends BaseTestCase
{
    public function test()
    {
        $result = Cron::run('demo_cron');
        $this->assertEquals('demo_run_return', $result);
    }
}