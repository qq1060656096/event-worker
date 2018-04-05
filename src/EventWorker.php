<?php
namespace Zwei\EventWork;

use Zwei\EventWork\Config\EventConfig;
use Zwei\EventWork\Config\EventModuleConfig;
use Zwei\EventWork\Event;


/**
 * 事件消费者(事件工作者)
 * Class EventWorker
 * @package Zwei\EventWork
 */
class EventWorker
{
    /**
     * 获取当前时间戳
     */
    public static function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    /**
     * 输出开始运行信息
     *
     * @param string $moduleName 模块名
     */
    public static function outputStartRunInfo($moduleName)
    {
        $date   = date('Y-m-d H:i:s');
        $str = <<<str

 start module: $moduleName
 start time: $date
 
str;
        echo $str;
    }
    /**
     * 运行模块
     * @param string $moduleName 模块名
     */
    public static final function run($moduleName)
    {
        // 输出开始运行信息
        self::outputStartRunInfo($moduleName);
        $eventConfig        = new EventConfig();
        $eventModuleConfig  = new EventModuleConfig();
        $eventConfig->validateEvents();// 验证事件是否配置正确
        $eventModuleConfig->validateModule($eventConfig);// 验证模块是否配置正确

        $event              = new Event($moduleName, $eventConfig, $eventModuleConfig);
        $class              = $eventModuleConfig->getModuleClass($moduleName);
        $callbackFunc       = $eventModuleConfig->getModuleCallbackFunc($moduleName);
        $oldTime            = self::microtime_float();
        while (true) {
            // 每个多少秒运行一次
            $sleepTime = 0.5;// 0.5秒
            $nowTime = self::microtime_float();
            if (($nowTime - $oldTime) < $sleepTime) {
                $usleepTime = $sleepTime - ($nowTime - $oldTime);
                $usleepTime = $usleepTime * 1000000;
                usleep($usleepTime);
            }
            $oldTime = $nowTime;

            $eventLogExecLists = $event->getExecLogs();
            foreach ($eventLogExecLists as $eventName => $eventLogLists) {
                foreach ($eventLogLists as $key => $eventRaw) {
                    try{
                        // 处理事件
                        $obj    = new $class();
                        $data   = json_decode($eventRaw['data'], true);
                        if (call_user_func_array([$obj, $callbackFunc], [$eventName, $eventRaw])) {
                            $event->doCallback($eventRaw['event'], [$eventRaw['id']]);
                        }
                    } catch (\Exception $e) {
                        throw $e;
                    }
                }
            }
        }
    }
}


