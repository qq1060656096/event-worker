<?php
namespace Wei\EventWork;

use Noodlehaus\Config;
use Wei\BriefDB\Common\Composer;

/**
 * 事件模块配置
 *
 * Class EventConfig
 * @package Wei\EventWork
 */
class EventConfig
{
    /**
     * 事件列表key
     */
    const EVENT_LISTS_KEY = 'event_lists';

    /**
     * 模块列表key
     */
    const MODULE_LISTS_KEY = 'module_lists';

    /**
     * 获取配置文件
     * @return \Noodlehaus\Config
     */
    public static function getConfigInstance()
    {
        return Config::load(Composer::getComposerVendorDir().'/config/event.conf.yml');
    }

    /**
     * 获取事件列表
     * @return array
     */
    public static function getEventLists()
    {
        return self::getConfigInstance()->get(self::EVENT_LISTS_KEY);
    }

    /**
     * 根据事件key获取事件id
     * @param string $eventKey
     * @return mixed
     */
    public static function getEventId($eventKey)
    {
        $eventLists = self::getEventLists();
        return $eventLists[$eventKey];
    }

    /**
     * 获取模块列表
     * @return array
     */
    public static function getModuleLists()
    {
        return self::getConfigInstance()->get(self::MODULE_LISTS_KEY);
    }

    /**
     * 根据模块key获取模块信息
     * @param string $moduleKey 模块key
     * @return array
     */
    public static function getModule($moduleKey)
    {
        $moduleLists = self::getModuleLists();
        return $moduleLists[$moduleKey];
    }

    /**
     * 获取模块id
     * @param string $moduleKey 模块key
     * @return string
     */
    public static function getModuleId($moduleKey)
    {
        return self::getModule($moduleKey)['id'];
    }

    /**
     * 获取模块调用类
     * @param string $moduleKey 模块key
     * @return string
     */
    public static function getModuleClass($moduleKey)
    {
        return self::getModule($moduleKey)['class'];
    }

    /**
     * 获取模块调用方法
     *
     * @param string $moduleKey 模块key
     * @return string
     */
    public static function getModuleCallback($moduleKey)
    {
        return self::getModule($moduleKey)['callback'];
    }

    /**
     * 获取模块监听事件列表
     * @param string $moduleKey 模块key
     * @return array
     */
    public static function getModuleListenEventLists($moduleKey)
    {
        $bool = empty(self::getModule($moduleKey)['listen_event_lists']);
        return $bool ? self::getEventLists() : self::getModule($moduleKey)['listen_event_lists'];
    }
}