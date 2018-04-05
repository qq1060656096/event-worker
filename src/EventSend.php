<?php
namespace Zwei\EventWork;


use Zwei\Base\DB;
use Zwei\EventWork\Config\EventConfig;

class EventSend extends Base
{
    /**
     * 事件配置
     *
     * @var \Zwei\EventWork\Config\EventConfig
     */
    protected $eventConfig = null;

    /**
     * 获取EventLog实例
     * @return EventSend
     */
    public static function getInstance()
    {
        static $obj = null;
        if ($obj=== null) {
            $obj = new EventSend();
            $obj->eventConfig = new EventConfig();
        }
        return $obj;
    }
    /**
     * 发送事件
     *
     * @param $eventKey 事件key
     * @param mixed $data 数据
     * @param string $user 用户
     * @return int|string 成功返回插入id, 否者失败
     */
    public static function send($eventName, $data, $user = '')
    {
        $eventSend  = self::getInstance();
        $eventId    = $eventSend->eventConfig->getEventId($eventName);
        $data = [
            'event' => $eventId,// 事件ID
            'user'  => $user,// 用户
            'data'  => json_encode($data),// json数据
            'ip'    => self::getIP(),
            'created' => time(),// 事件事件
        ];
        $dbConnection   = DB::getInstance()->getConnection();
        $result         = $dbConnection->insert($eventSend->getEventLogTableName(), $data);
        $insertId       = $result ? $dbConnection->lastInsertId() : $result;
        return $insertId;
    }
}