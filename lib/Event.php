<?php
namespace Wei\EventWork;

use Noodlehaus\Config;
use Wei\BriefDB\Common\Composer;
use Wei\EventWork\Exception\ParamException;

/**
 * 事件
 *
 * Class Event
 * @package Wei\EventWork
 */
class Event extends Base
{
    /**
     * 事件记录对象
     * @var EventLog
     */
    protected $eventLog = null;

    /**
     * 事件模块对象
     *
     * @var EventModule
     */
    protected $eventModule = null;

    /**
     * 模块key
     * @var string
     */
    protected $moduleKey = null;

    /**
     * 构造方法初始化
     * @param string $moduleKey 模块key
     */
    public function __construct($moduleKey)
    {
        $this->setModuleKey($moduleKey);
        $this->init();
    }

    /**
     * 初始化
     */
    public function init()
    {
        $this->eventLog     = new EventLog();
        $this->eventModule  = new EventModule();
    }

    /**
     * 设置模块key
     * @param string $moduleKey 模块key
     * @throws ParamException
     */
    public function setModuleKey($moduleKey)
    {
        switch (true) {
            case empty($moduleKey):
                throw new ParamException('模块key参数不能为空');
                break;
            case empty(EventConfig::getModule($moduleKey)):
                throw new ParamException('模块key不存在');
                break;
        }
        $this->moduleKey = $moduleKey;
    }

    /**
     * 获取模块key
     * @return string
     * @throws ParamException
     */
    public function getModuleKey()
    {
        if (empty($this->moduleKey)) {
            throw new ParamException('没有设置模块key');
        }
        return $this->moduleKey;
    }

    /**
     * 获取要执行的事件记录
     *
     * @return array
     */
    public function getExecLogs()
    {
        $moduleKey  = $this->getModuleKey();
        $moduleId   = EventConfig::getModuleId($moduleKey);
        $listenEventLists = EventConfig::getModuleListenEventLists($moduleKey);
        // 要执行的日志
        $execLogs = array();
        foreach ($listenEventLists as $key => $eventKey) {
            $eventId = EventConfig::getEventId($eventKey);
            // 获取模块执行记录
            $moduleRecord   = $this->eventModule->getModule($moduleId, $eventId);
            $moduleEventIds = $moduleRecord['event_ids'];
            $moduleLastId   = $moduleRecord['last_id'];
            $newLastId      = $this->eventLog->getLastId($eventId);
            // 当前模块事件都执行完了
            if ($newLastId <= $moduleLastId && count($moduleEventIds) == 0) {
                continue;
            }
            // 为执行的事件日志记录
            $execLogs[$eventKey] = $this->eventLog->getLog($moduleLastId, $eventId, $moduleEventIds);
            $ids = [];
            foreach ($execLogs[$eventKey] as $eventRecord) {
                $ids[] = $eventRecord['id'];
            }

            // 设置模块未执行的记录
            $this->eventModule->setModule($moduleId, $eventId, $newLastId, $ids);
        }
        return $execLogs;
    }

    /**
     * 执行完事件后,设置事件已经执行了
     * @param string $eventKey 事件key
     * @param array $ids 已执行的事件ids
     */
    public function doCallback($eventKey, array $ids)
    {
        $moduleKey  = $this->getModuleKey();
        $moduleId   = EventConfig::getModuleId($moduleKey);
        $eventId    = EventConfig::getEventId($eventKey);
        // 获取模块执行记录
        $moduleRecord   = $this->eventModule->getModule($moduleId, $eventId);
        $eventIds       = $moduleRecord['event_ids'];
        $ids            = array_diff($eventIds, $ids);
        // 设置模块未执行的记录
        $this->eventModule->setModule($moduleId, $eventId, 0, $ids);
    }
}