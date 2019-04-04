<?php

/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2019/2/28
 * @since          1.0
 */
class Min
{

    /**
     * version
     */
    const VERSION = '1.0.0';

    /**
     * @var $_app
     */
    public static $_app;

    /**
     * @class \min\di\Container $container
     */
    public static $container;


    /**
     * @param $type
     * @param array $params
     * @return mixed
     */
    public static function createObject($type, array $params = [])
    {
        if (is_string($type)) {
            return static::$container->get($type, $params);
        } elseif (is_array($type) && isset($type['class'])) {
            $class = $type['class'];
            unset($type['class']);
            return static::$container->get($class, $params, $type);
        } elseif (is_callable($type, true)) {
            return static::$container->invoke($type, $params);
        } elseif (is_array($type)) {
            throw new \min\exception\InvalidConfigException('Object configuration must be an array containing a "class" element.');
        }

        throw new \min\exception\InvalidConfigException('Unsupported configuration type: ' . gettype($type));
    }

    /**
     * 配置类属性
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
}