<?php
namespace Zwei\EventWork;

use Zwei\Base\DB;
use Zwei\EventWork\Config\EventModuleConfig;
use Zwei\EventWork\Exception\ParamException;
use Zwei\EventWork\Config\EventConfig;

/**
 * 事件
 *
 * Class Event
 * @package Wei\EventWork
 */
class Event extends Base
{


    /**
     * 模块名
     * @var null|string
     */
    protected $moduleName = null;

    /**
     * 模块监听事件列
     * @var array
     */
    protected $moduleListenEventLists = [];// [事件名 => 事件id]
    /**
     * 事件配置
     * @var null|EventConfig
     */
    protected $eventConfig = null;

    /**
     * 事件模块配置
     * @var null|EventModuleConfig
     */
    protected $eventModuleConfig = null;

    /**
     * Event constructor.
     * @param string $moduleName 模块名
     * @param EventConfig $eventConfig 事件配置类
     * @param EventModuleConfig $eventModuleConfig 事件模块配置类
     */
    public function __construct($moduleName, EventConfig $eventConfig, EventModuleConfig $eventModuleConfig)
    {
        $this->moduleName           = $moduleName;
        $this->eventConfig          = $eventConfig;
        $this->eventModuleConfig    = $eventModuleConfig;

        // 设置监听事件列表
        $moduleListenEventLists     = $this->eventModuleConfig->getModuleListenEvents($this->moduleName);
        $moduleListenEventListsNew  = [];
        foreach ($moduleListenEventLists as $eventName) {
            $moduleListenEventListsNew[$eventName] = $this->eventConfig->getEventId($eventName);
        }
        unset($moduleListenEventLists);
        $this->moduleListenEventLists = $moduleListenEventListsNew;
    }


    /**
     * 根据事件id获取事件日志最后一个id
     * @param integer $eventId
     * @return integer
     */
    public function getLastEventLogId($eventId)
    {
        // 获取事件最后一个ID
        $whereArr = [$eventId];
        $dbConnection = DB::getInstance()->getConnection();
        $sql    = "select id from {$this->getEventLogTableName()} where event = ? order by id desc limit 1;";
        $id     = $dbConnection->fetchColumn($sql, $whereArr);
        $id     = $id ? $id : 0;
        return $id;
    }

    /**
     * 查询模块未执行的事件日志记录列表
     *
     * @param integer $moduleLastEventId 模块最后一次执行的事件日志id
     * @param integer $eventId 事件id
     * @param array $moduleUnExecEventIds 模块未执行的事件列表
     * @return array
     */
    public function getEventLog($moduleLastEventId, $eventId, array $moduleUnExecEventIds)
    {
        $whereStr = '';
        if (count($moduleUnExecEventIds) > 0) {
            $whereStr = " or id in(".implode(",", $moduleUnExecEventIds).") ";
        }
        $sql = "SELECT * FROM {$this->getEventLogTableName()} WHERE event = {$eventId} and (id > {$moduleLastEventId} {$whereStr}) ORDER BY id asc";
        $dbConnection = DB::getInstance()->getConnection();
        $lists = $dbConnection->fetchAll($sql);
        return $lists;
    }

    /**
     * 获取指定模块指定事件最后记录
     *
     * @param string $moduleName 模块名
     * @param integer $eventId 模块id
     * @return array
     */
    public function getLastEventModuleLog($moduleName, $eventId)
    {
        // 获取模块上一次执行结果
        $dbConnection = DB::getInstance()->getConnection();
        $sql    = "select * from {$this->getEventModuleLogTableName()} where module_name = '{$moduleName}' and event_id = {$eventId} order by mid desc limit 1";
        $row    = $dbConnection->fetchAssoc($sql);
        if(!$row){
            // 防止未运行过得模块执行之前的事件
            $event_log_last_id = $this->getLastEventLogId($eventId);
            if (!$event_log_last_id) {
                $event_log_last_id = 0;
            }
            $row = [
                'module_name'       => $moduleName,
                'event_id'          => $eventId,//
                'event_log_last_id' => $event_log_last_id,// 最后执行的event_log.id
                'event_log_ids'     => '',// event_log表:未执行的ids"逗号分隔",示例(1,2,3,4,5)
            ];
            $dbConnection->insert($this->getEventModuleLogTableName(), $row);
        }
        $row['event_log_ids'] = $row['event_log_ids'] ? explode(',', $row['event_log_ids']) : [];
        return $row;
    }

    /**
     * 设置模块执行记录
     *
     * @param string $moduleName 模块名
     * @param string $eventId 事件id
     * @param string $lastId 本次执行事件记录id, 大于零才设置event_module.last_id字段
     * @param array $moduleUnExecEventIds 未执行事件记录ids
     * @return integer 成功返回受影响行数
     */
    public function setModuleExecRecord($moduleName, $eventId, $eventLogLastId, array $moduleUnExecEventIds)
    {
        $eventIdsStr = implode(',', $moduleUnExecEventIds);
        $data = [
            'event_log_ids' => $eventIdsStr,
        ];
        if ($eventLogLastId > 0) {
            $data['event_log_last_id'] = $eventLogLastId;
        }

        $where = [
            'module_name' => $moduleName,
            'event_id' => $eventId,
        ];

        $dbConnection = DB::getInstance()->getConnection();
        $result = $dbConnection->update($this->getEventModuleLogTableName(), $data, $where);
        return $result;
    }



    /**
     * 获取要执行的事件记录
     *
     * @return array
     */
    public function getExecLogs()
    {
        $moduleName = $this->moduleName;
        // 要执行的日志
        $execLogs = array();
        foreach ($this->moduleListenEventLists as $eventName => $eventId) {
            // 获取模块上一次执行结果
            $moduleLogInfo          = $this->getLastEventModuleLog($moduleName, $eventId);
            $moduleUnExecEventIds   = $moduleLogInfo['event_log_ids'];// 模块上次未执行完的事件id
            $moduleExecLastEventId  = $moduleLogInfo['event_log_last_id'];// 模块上次执行的最后一个事件id
            $newEventLastId         = $this->getLastEventLogId($eventId);
            // 当前模块事件都执行完了
            if ($newEventLastId <= $moduleExecLastEventId && count($moduleUnExecEventIds) == 0) {
                continue;
            }
            // 未执行的事件日志记录
            $execLogs[$eventName] = $this->getEventLog($moduleExecLastEventId, $eventId, $moduleUnExecEventIds);
            $ids = [];
            foreach ($execLogs[$eventName] as $eventRecord) {
                $ids[] = $eventRecord['id'];
            }
            // 设置模块未执行的记录
            $this->setModuleExecRecord($moduleName, $eventId, $newEventLastId, $ids);
        }
        return $execLogs;
    }

    /**
     * 执行完事件后,设置事件已经执行了
     * @param string $eventName 事件名
     * @param array $ids 已执行的事件ids
     */
    public function doCallback($eventId, array $ids)
    {
        // 获取模块执行记录
        $moduleRecord   = $this->getLastEventModuleLog($this->moduleName, $eventId);
        $eventIds       = $moduleRecord['event_log_ids'];
        $ids            = array_diff($eventIds, $ids);
        // 设置模块未执行的记录
        $this->setModuleExecRecord($this->moduleName, $eventId, 0, $ids);
    }
}