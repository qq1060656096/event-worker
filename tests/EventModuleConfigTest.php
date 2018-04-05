<?php
namespace Zwei\EventWork\Tests;

use Zwei\EventWork\Config\EventConfig;
use Zwei\EventWork\Config\EventModuleConfig;

/**
 * 测试模块配置
 *
 * Class EventModuleConfigTest
 * @package Zwei\EventWork\Tests
 */
class EventModuleConfigTest extends BaseTestCase
{
    /**
     * 测试先配置事件
     * @expectedException \Zwei\EventWork\Exception\ConfigException
     * @expectedExceptionMessageRegExp /Please first configure modules/
     */
    public function testFirstConfigureEvents()
    {
        $prefix = 'test-module1';
        $fileName = 'tests/test-module-configure-modules-error.conf.yml';
        $obj = new EventModuleConfig($prefix, $fileName);
        $obj->validateModule(new EventConfig($prefix, $fileName));
    }

    /**
     * 测试模块class配置错误
     * @expectedException \Zwei\EventWork\Exception\ConfigException
     * @expectedExceptionMessageRegExp /Module '(.*)' class error/
     */
    public function testClassError()
    {

        $prefix = 'test-module2';
        $fileName = 'tests/test-module-class-error.conf.yml';
        $obj = new EventModuleConfig($prefix, $fileName);
        $obj->validateModule(new EventConfig($prefix, $fileName));
    }

    /**
     * 测试模块callback_func配置错误
     * @expectedException \Zwei\EventWork\Exception\ConfigException
     * @expectedExceptionMessageRegExp /Module '(.*)' callback_func error/
     */
    public function testCallbackFuncError()
    {

        $prefix = 'test-module2';
        $fileName = 'tests/test-module-callback-func-error.conf.yml';
        $obj = new EventModuleConfig($prefix, $fileName);
        $obj->validateModule(new EventConfig($prefix, $fileName));
    }

    /**
     * 测试模块配置监听事件错误
     * @expectedException \Zwei\EventWork\Exception\ConfigException
     * @expectedExceptionMessageRegExp /Module '(.*)' listen events error/
     */
    public function testListenEvents()
    {

        $prefix = 'test-module2';
        $fileName = 'tests/test-module-listen-event-error.conf.yml';
        $obj = new EventModuleConfig($prefix, $fileName);
        $obj->validateModule(new EventConfig($prefix, $fileName));
    }

    /**
     * 测试模块获取配置信息
     */
    public function test()
    {
        $obj = new EventModuleConfig();
        $obj->validateModule(new EventConfig());
        $lists = $obj->getModules();
        $this->assertEquals($lists['docker_module'], $obj->getModule('docker_module'));
        $this->assertEquals($lists['docker_module']['class'], $obj->getModuleClass('docker_module'));
        $this->assertEquals($lists['docker_module']['callback_func'], $obj->getModuleCallbackFunc('docker_module'));
        $this->assertEquals($lists['docker_module']['listen_events'], $obj->getModuleListenEvents('docker_module'));
    }
}