<?php
namespace Zwei\EventWork\Tests;

use Zwei\EventWork\EventWorker;

/**
 * 测试事件发送
 *
 * Class EventWorkTest
 * @package Zwei\EventWork\Tests
 */
class EventWorkTest extends BaseTestCase
{
    /**
     * @codeCoverageIgnore
     */
    public function test()
    {
        $this->markTestSkipped('Please comment this line code. ');
        EventWorker::run("docker_module");
    }
}