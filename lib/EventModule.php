<?php
namespace Wei\EventWork;

/**
 * 事件模块
 * Class EventModule
 * @package Wei\EventWork
 */
class EventModule extends Base
{
    const TABLE_NAME = 'event_module';
    /**
     * 获取模块上次执行结果(数据库中原始数据)
     *
     * @param integer $moduleId 模块id
     * @param integer $eventId 事件id
     * @return array
     */
    public function getByModuleType($moduleId, $eventId)
    {
        // 获取模块上一次执行结果
        $result = Db::getSelect()->from(self::TABLE_NAME)
            ->condition('module', $moduleId)
            ->condition('event', $eventId)
            ->findOne();
        return $result;
    }

    /**
     * 创建模块记录
     *
     * @param integer $moduleId 模块id
     * @param integer $eventId 事件id
     * @return int|string 返回受影响行数
     */
    public function create($moduleId, $eventId)
    {
        $data = [
            'module' => $moduleId,// 模块
            'event' => $eventId,// 事件ID
            'last_id' => 0,// 最后执行的event_log.id,
            'event_ids' => '',//  未执行的ids
        ];
        $result = Db::getInsert()->from(self::TABLE_NAME)->insert($data);
        return $result;
    }

    /**
     * 获取模块上次执行结果(数据库中取出数据处理后的)
     *
     * @param integer $moduleId 模块
     * @param integer $eventId 事件
     * @return array
     * @see EventModule::getModule()
     */
    public function getModule($moduleId, $eventId)
    {
        $result = $this->getByModuleType($moduleId, $eventId);
        if (!$result) {
            $result = [
                'module' => $moduleId,// 模块
                'event' => $eventId,// 事件ID
                'last_id' => 0,// 最后执行的event_log.id,
                'event_ids' => '',//  未执行的ids
            ];
            $this->create($moduleId, $eventId);
        } else {
        }
        $result['event_ids'] = $result['event_ids'] ? explode(',', $result['event_ids']) : [];
        return $result;
    }

    /**
     * 设置模块执行记录
     *
     * @param string $moduleId 模块id
     * @param string $eventId 事件id
     * @param string $lastId 本次执行事件记录id, 大于零才设置event_module.last_id字段
     * @param array $eventIds 未执行事件记录ids
     * @return integer 成功返回受影响行数
     */
    public function setModule($moduleId, $eventId, $lastId, array $eventIds)
    {
        $eventIdsStr = implode(',', $eventIds);
        $data = [
            'event_ids' => $eventIdsStr,
        ];
        if ($lastId > 0) {
            $data['last_id'] = $lastId;
        }

        return Db::getUpdate()->from(self::TABLE_NAME)
            ->condition('module', $moduleId)
            ->condition('event', $eventId)
            ->update($data);
    }
}