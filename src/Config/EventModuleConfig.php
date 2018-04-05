<?php
namespace Zwei\EventWork\Config;

use Zwei\EventWork\Exception\ConfigException;

class EventModuleConfig extends ConfigBase
{

    /**
     * 模块列表配置键名
     */
    const MODULES_KEY = 'modules';


    /**
     * 验证模块配置是否正确(失败跑出异常)
     * @param EventConfig $eventConfig
     */
    public function validateModule(EventConfig $eventConfig)
    {
        $lists = $this->getModules();
        $count = count($lists);
        // 请先配置事件
        if ($count < 1 || !is_array($lists)) {
            $errorMsg = sprintf("Please first configure modules");
            throw new ConfigException($errorMsg);
        }

        // 获取模块所有监听事件
        foreach ($lists as $moduleName => $moduleInfo) {
            // 检测模块调用类错误
            if (empty($moduleInfo['class'])) {
                $errorMsg = sprintf("Module '%s' class error.", $moduleName);
                throw new ConfigException($errorMsg);
            }
            // 检测模块调用方法错误
            if (empty($moduleInfo['callback_func'])) {
                $errorMsg = sprintf("Module '%s' callback_func error.", $moduleName);
                throw new ConfigException($errorMsg);
            }
            // 检测模块事件配置错误
            $listenEvents = $this->getModuleListenEvents($moduleName);
            $events = $eventConfig->getEvents();
            $eventsKey = array_keys($events);
            $errorListenEvents = array_diff($listenEvents, $eventsKey);
            if ($errorListenEvents) {
                $errorListenEventsStr = print_r($errorListenEvents, true);
                $errorMsg = sprintf("Module '%s' listen events error: %s", $moduleName, $errorListenEventsStr);
                throw new ConfigException($errorMsg);
            }
        }
    }

    /**
     * 获取模块列表
     * @return array
     */
    public function getModules()
    {
        return $this->get(self::MODULES_KEY);
    }

    /**
     * 根据模块名获取模块信息
     * @param string $moduleName 模块名
     * @return array
     */
    public function getModule($moduleName)
    {
        $lists = $this->getModules();
        return $lists[$moduleName];
    }

    /**
     * 获取模块id
     *
     * @param string $moduleName 模块名
     * @return mixed
     */
    public function getModuleId($moduleName)
    {
        return $this->getModule($moduleName)['id'];
    }

    /**
     * 获取模块调用类
     * @param string $moduleName 模块名
     * @return string
     */
    public function getModuleClass($moduleName)
    {
        return $this->getModule($moduleName)['class'];
    }

    /**
     * 获取模块调用方法
     *
     * @param string $moduleName 模块名
     * @return string
     */
    public function getModuleCallbackFunc($moduleName)
    {
        return $this->getModule($moduleName)['callback_func'];
    }

    /**
     * 获取模块监听事件列表
     * @param string $moduleName 模块名
     * @return array
     */
    public function getModuleListenEvents($moduleName)
    {
        return $this->getModule($moduleName)['listen_events'];
    }
}