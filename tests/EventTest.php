<?php
namespace Zwei\EventWork\Tests;

use Zwei\EventWork\Event;
use Zwei\EventWork\Config\EventConfig;
use Zwei\EventWork\Config\EventModuleConfig;


/**
 * 测试事件
 *
 * Class EventTest
 * @package Zwei\EventWork\Tests
 */
class EventTest extends BaseTestCase
{
    public function test()
    {
        $eventConfig        = new EventConfig();
        $eventModuleConfig  = new EventModuleConfig();
        $obj    = new Event('docker_module', $eventConfig, $eventModuleConfig);
        $lists  = $obj->getExecLogs();
        print_r($lists);
    }
}