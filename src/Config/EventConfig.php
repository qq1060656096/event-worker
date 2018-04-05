<?php
namespace Zwei\EventWork\Config;

use Zwei\EventWork\Exception\ConfigException;

/**
 * Class EventConfig
 * @package Zwei\EventWork\Config
 */
class EventConfig extends ConfigBase
{
    /**
     * 事件列表配置键名
     */
    const EVENTS_KEY = 'events';

    /**
     * 验证事件配置是否正确(失败跑出异常)
     */
    public function validateEvents()
    {
        $lists = $this->getEvents();
        $count = count($lists);// 事件数量
        // 请先配置事件
        if ($count < 1 || !is_array($lists)) {
            $errorMsg = sprintf("Please first configure events");
            throw new ConfigException($errorMsg);
        }

        // 检测事件是否使用了相同的事件id
        // 获取重复的事件ids
        $duplicateEventIds = [];
        foreach ($lists as $event => $eventId) {
            $duplicateEventIds[$eventId][]  = $event;
        }
        foreach ($duplicateEventIds as $eventId => $eventKeys) {
            // 事件id重复
            if(count($eventKeys) > 1){
                $duplicateEventKeys = implode(',', $eventKeys);
                $errorMsg = sprintf("Can not duplicate event id. \n events %s use same event id(%s)", $duplicateEventKeys, $eventId);
                throw new ConfigException($errorMsg);
            }
        }
    }

    /**
     * 获取事件列表
     * @return array
     */
    public function getEvents()
    {
        return $this->get(self::EVENTS_KEY);
    }

    /**
     * 根据事件名获取事件id
     * @param string $name 事件名
     * @return mixed
     */
    public function getEventId($name)
    {
        $lists = $this->getEvents();
        return $lists[$name];
    }
}