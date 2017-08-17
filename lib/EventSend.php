<?php
namespace Wei\EventWork;

/**
 * 事件发送
 *
 * Class EventSend
 * @package Wei\EventWork
 */
class EventSend
{
    /**
     * EventLog实例
     * @var EventLog
     */
    private static $eventLog = null;

    /**
     * 获取EventLog实例
     * @return EventLog
     */
    protected static function getEventLogInstance()
    {
        self::$eventLog === null ? self::$eventLog = new EventLog() : self::$eventLog;
        return self::$eventLog;
    }

    /**
     * 发送事件
     *
     * @param $eventKey 事件key
     * @param mixed $data 数据
     * @param string $user 用户
     * @return int|string 成功返回插入id
     */
    public static function send($eventKey, $data, $user = '')
    {
        $eventId = EventConfig::getEventId($eventKey);
        return self::getEventLogInstance()->add($eventId, $data, $user);
    }
}