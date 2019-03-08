<?php

/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2018/6/28
 * @since          1.0
 */

namespace min\base;

abstract class BaseObject
{
    /**
     * 初始化配置文件
     * BaseObject constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            self::configure($this, $config);
        }
        $this->init();
    }

    /**
     * 初始化配置
     * @param $object
     * @param $properties
     * @return mixed
     */
    public static function configure($object, $properties)
    {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }

        return $object;
    }

    /**
     * 集成后-初始化
     */
    public function init()
    {
    }

}
