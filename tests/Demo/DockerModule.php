<?php
namespace Zwei\EventWork\Tests\Demo;

/**
 * 测试模块
 *
 * Class Docker
 * @package Zwei\EventWork\Tests\Demo
 */
class DockerModule
{
    /**
     * @return bool true执行成功,执行失败
     */
    public function run($evenName, $event){
        echo 123;
        var_dump($evenName);
        print_r($event);
        return true;
    }
}