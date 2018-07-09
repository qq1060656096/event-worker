<?php
namespace Zwei\EventWork\Tests;

use Zwei\EventWork\Config\CronConfig;
use Zwei\EventWork\Config\EventConfig;

/**
 * 测试计划任务配置
 *
 * Class EventConfigTest.php
 * @package Zwei\EventWork\Tests
 */
class CronConfigTest extends BaseTestCase
{
    /**
     * 测试先配置计划任务
     * @expectedException \Zwei\EventWork\Exception\ConfigException
     * @expectedExceptionMessageRegExp /(Please first configure crons)/
     */
    public function testFirstConfigureEvents()
    {
        $obj = new CronConfig('test-event1', 'tests/test-cron-configure-crons-error.conf.yml');
        $obj->validate();
    }

    /**
     * 测试计划任务class配置错误
     * @expectedException \Zwei\EventWork\Exception\ConfigException
     * @expectedExceptionMessageRegExp /Cron '(.*)' class error/
     */
    public function testClassError()
    {

        $prefix = 'test-module2';
        $fileName = 'tests/test-cron-class-error.conf.yml';
        $obj = new CronConfig($prefix, $fileName);
        $obj->validate();
    }

    /**
     * 测试计划任务callback_func配置错误
     * @expectedException \Zwei\EventWork\Exception\ConfigException
     * @expectedExceptionMessageRegExp /Cron '(.*)' callback_func error/
     */
    public function testCallbackFuncError()
    {

        $prefix = 'test-module2';
        $fileName = 'tests/test-cron-callback-func-error.conf.yml';
        $obj = new CronConfig($prefix, $fileName);
        $obj->validate();
    }

    /**
     * 测试事件信息获取
     */
    public function test()
    {
        $obj = new CronConfig();
        $lists = $obj->getCrons();
        $info = $obj->getCron('demo_cron');
        $this->assertEquals($lists['demo_cron'], $info);
    }
}