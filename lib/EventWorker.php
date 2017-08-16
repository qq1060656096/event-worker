<?php
namespace Wei\EventWork;
use Wei\BriefDB\Common\Composer;
use Wei\EventWork\Exception\ParamException;


/**
 * 事件消费者(事件工作者)
 * Class EventWorker
 * @package Wei\EventWork
 */
class EventWorker extends Base
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
     * @param string $moduleKey 模块信息
     */
    public static function outputStartRunInfo($moduleKey)
    {
        $date   = date('Y-m-d H:i:s');
        $str = <<<str

 start module: $moduleKey
 start time: $date
 
str;
        echo $str;
    }
    /**
     * 运行模块
     * @param string $moduleKey 模块key
     */
    public static final function run($moduleKey)
    {
        $event  = new Event($moduleKey);
        // 输出开始运行信息
        self::outputStartRunInfo($moduleKey);

        $class      = EventConfig::getModuleClass($moduleKey);
        $callback   = EventConfig::getModuleCallback($moduleKey);
        $oldTime = self::microtime_float();
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
            foreach ($eventLogExecLists as $eventKey => $eventLogLists) {
                foreach ($eventLogLists as $key => $eventRaw) {
                    try{
                        // 处理事件
                        $obj    = new $class();
                        $data   = json_decode($eventRaw['data'], true);
                        if (call_user_func_array([$obj, $callback], [$eventKey, $data, $eventRaw])) {
                            $event->doCallback($eventKey, [$eventRaw['id']]);
                        }
                    } catch (\Exception $e) {
                        echo $e;
                        throw $e;
                    }
                }
            }
        }
    }
}


