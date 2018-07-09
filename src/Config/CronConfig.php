<?php
namespace Zwei\EventWork\Config;

use Zwei\EventWork\Exception\ConfigException;

/**
 * Class EventConfig
 * @package Zwei\EventWork\Config
 */
class CronConfig extends ConfigBase
{
    /**
     * 计划任务列表配置键名
     */
    const CRONS_KEY = 'crons';

    /**
     * 验证事件配置是否正确(失败跑出异常)
     */
    public function validate()
    {
        $lists = $this->getCrons();
        $count = count($lists);// 数量
        // 请先配置
        if ($count < 1 || !is_array($lists)) {
            $errorMsg = sprintf("Please first configure crons");
            throw new ConfigException($errorMsg);
        }

        // 获取所有计划任务列表
        foreach ($lists as $name => $rowInfo) {
            // 检测模块调用类错误
            if (empty($rowInfo['class'])) {
                $errorMsg = sprintf("Cron '%s' class error.", $name);
                throw new ConfigException($errorMsg);
            }
            // 检测模块调用方法错误
            if (empty($rowInfo['callback_func'])) {
                $errorMsg = sprintf("Cron '%s' callback_func error.", $name);
                throw new ConfigException($errorMsg);
            }
        }
    }

    /**
     * 获取事计划任务列表
     * @return array
     */
    public function getCrons()
    {
        return $this->get(self::CRONS_KEY);
    }

    /**
     * 获取计划任务配置信息
     *
     * @param string $name 计划任务名
     * @return mixed
     */
    public function getCron($name)
    {
        $lists = $this->getCrons();
        return $lists[$name];
    }

    /**
     * 获取计划任务调用类
     * @param string $name 计划任务名
     * @return string
     */
    public function getCronClass($name)
    {
        return $this->getCron($name)['class'];
    }

    /**
     * 获取计划任务调用方法
     *
     * @param string $name 计划任务名
     * @return string
     */
    public function getCronCallbackFunc($name)
    {
        return $this->getCron($name)['callback_func'];
    }
}