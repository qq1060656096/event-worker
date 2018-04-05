<?php
namespace Zwei\EventWork\Config;

use Zwei\Base\Config;

/**
 * 配置基类
 *
 * Class ConfigBase
 * @package Zwei\EventWork\Config
 */
class ConfigBase
{
    /**
     * 配置前缀(避免冲突)
     * @var string
     */
    protected $defaultPrefix = null;

    /**
     * 配置文件名
     * @var string
     */
    protected $fileName = '';

    /**
     * ConfigBase constructor.
     * @param string $prefix 配置前缀(避免冲突)
     * @param string $fileName 配置文件名
     */
    public function __construct($prefix = '', $fileName = '')
    {
        $this->setDefaultPrefix($prefix);
        $this->setFileName($fileName);
    }

    /**
     * 变量前缀,避免冲突
     * @return string
     */
    public function getDefaultPrefix()
    {
        if (!$this->defaultPrefix)
            $this->defaultPrefix = 'event-worker';
        return $this->defaultPrefix;
    }

    /**
     * 设置默认前缀
     * @param string $defaultPrefix
     */
    public function setDefaultPrefix($defaultPrefix)
    {
        $this->defaultPrefix = $defaultPrefix;
    }

    /**
     * 获取配置文件
     *
     * @return string
     */
    public function getFileName()
    {
        if (!$this->fileName)
            $this->fileName = 'event-worker.conf.yml';
        return $this->fileName;
    }

    /**
     * 设置配置文件名
     * @param string $fileName 配置文件名
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * 获取配置
     *
     * @param string $name 键名
     * @return mixed
     */
    public function get($name)
    {
        var_dump($this->getDefaultPrefix());
        var_dump($this->getFileName());
        return Config::get($name, $this->getDefaultPrefix(), $this->getFileName());
    }
}