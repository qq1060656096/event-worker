<?php
namespace Wei\EventWork;

use Wei\BriefDB\Database\Query\Condition;

/**
 * 事件日志
 *
 * Class EventLog
 * @package Wei\EventWork
 */
class EventLog extends Base
{
    /**
     * 表名
     */
    const TABLE_NAME = 'event_log';

    /**
     * 根据事件id获取事件日志最后一个id
     * @param integer $eventId
     * @return integer
     */
    public function getLastId($eventId)
    {
        // 获取事件最后一个ID
        $params = [$eventId];
        $result = Db::getSelect()->from(self::TABLE_NAME)
            ->fields(['id'])
            ->condition('event', $eventId)->orderBy('id', 'DESC')->limit(1)->findOne();
        $id     = $result ? intval($result['id']) : 0;
        return $id;
    }

    /**
     * 查询模块未执行的事件日志记录列表
     *
     * @param integer $moduleLastId 模块最后一次执行的事件日志id
     * @param integer $eventId 事件id
     * @param array $moduleEventIds 未执行的事件列表
     * @return array
     */
    public function getLog($moduleLastId, $eventId, array $moduleEventIds)
    {
        // select * from event_log where （id = $moduleLastId or id in({implode(',', $moduleErrorIds})) and event = {$eventId}
        // SELECT * FROM event_log WHERE (id > '0' OR id IN ( '1','2','3' )) AND event = '2' ORDER BY id DESC
        $condition  = new Condition('OR');
        $condition->condition('id', $moduleLastId, '>');
        if ($moduleEventIds) {
            $condition->condition('id', $moduleEventIds);
        }

        return Db::getSelect()->from(self::TABLE_NAME)
            ->condition($condition)
            ->condition('event', $eventId)
            ->orderBy('id', 'ASC')
            ->findAll();
    }
    

    /**
     * 添加事件日志记录
     *
     * @param integer $eventId 事件ID
     * @param mixed $data 数据
     * @param string $user 用户
     * @return int|string 成功返回插入id,否则失败
     */
    public function add($eventId, $data, $user = '')
    {
        $data = [
            'event' => $eventId,// 事件ID
            'user' => $user,// 用户
            'data' => json_encode($data),// json数据
            'ip'   => self::getIP(),
            'created' => time(),// 事件事件
        ];
        $result = Db::getInsert()->from(self::TABLE_NAME)->insert($data);
        return $result ? Db::getInsert()->getLastInsertId() : $result;
    }


}