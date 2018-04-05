<?php
namespace Zwei\EventWork\Tests;

use Zwei\EventWork\Config\EventConfig;

/**
 * 测试事件配置
 *
 * Class EventConfigTest
 * @package Zwei\EventWork\Tests
 */
class EventConfigTest extends BaseTestCase
{
    /**
     * 测试先配置事件
     * @expectedException \Zwei\EventWork\Exception\ConfigException
     * @expectedExceptionMessageRegExp /(Please first configure events)/
     */
    public function testFirstConfigureEvents()
    {
        $obj = new EventConfig('test-event1', 'tests/test-event-configure-events-error.conf.yml');
        $obj->validateEvents();
    }

    /**
     * 测试重复的事件id
     * @expectedException \Zwei\EventWork\Exception\ConfigException
     * @expectedExceptionMessageRegExp /Can not duplicate event id/
     */
    public function testDuplicateEventId()
    {
        $obj = new EventConfig('test-event2', 'tests/test-event-duplicate-event-id-error.conf.yml');
        $obj->validateEvents();
    }

    /**
     * 测试事件信息获取
     */
    public function test()
    {
        $obj = new EventConfig();
        $events = $obj->getEvents();
        $eventId = $obj->getEventId('APP_CREATE');
        $this->assertEquals($events['APP_CREATE'], $eventId);
    }
}