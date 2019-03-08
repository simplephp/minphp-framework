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
     * @var $container
     */
    public static $container;

    /**
     * @param $name
     * @return mixed
     */
    public static function createObject($name) {
        return $name;
    }
}