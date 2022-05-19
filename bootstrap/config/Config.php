<?php

namespace bemibbs\config;

/**
 * @author Lingqi <3615331065@qq.com>
 * @time 2022-05-17 13:32
 */
class Config
{
    public array $config = [];

    public function __construct()
    {
        $this->config = include dirname(__DIR__, 2) . '/config/config.php';
    }

    /**
     * 获取所有配置
     * @return mixed
     */
    public  function getAll()
    {
        return $this->config;
    }

    /**
     * 读取配置项
     * @param $path
     * @return mixed|string
     */
    public function get($path)
    {
        if (is_string($path)) {
            return $this->config[$path];
        }
        if (is_array($path)) {
            foreach ($this->config as $k => $v) {
                foreach ($path as $key => $value) {
                    if (is_array($v)) {
                        foreach ($v as $name => $item) {
                            if ($name === $value) {
                                return $this->config[$key][$name];
                            }
                        }
                    }
                }
            }
        }
        return "Unknown Type";
    }
}