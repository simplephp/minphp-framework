<?php
/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2019/3/22
 * @since          1.0
 */

namespace min\base;

use Swoole\Coroutine;

trait Singleton
{
    private static $instance;

    private static $coInstances;

    /**
     * @param array ...$args
     * @return static
     */
    public static function getInstance(...$args)
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(...$args);
        }
        return self::$instance;
    }

    /**
     * @param mixed ...$args
     * @return mixed
     * @desc 协程内的单例
     */
    public static function getCoInstance(...$args)
    {
        $coId = Coroutine::getId();
        if (!isset(self::$coInstances[$coId])) {
            self::$coInstances[$coId] = new static(...$args);
            defer(function () use ($coId) {
                unset(self::$coInstances[$coId]);
            });
        }
        return self::$coInstances[$coId];
    }

    /**
     * @param mixed ...$args
     * @return mixed
     * @desc 请求级别的单例，用此方法：
     * @desc 同一请求内开协程，需要调用Family\Coroutine\Coroutine::create方法创建
     * @desc 不能直接用祼go创建
     */
    public static function getRequestInstance(...$args)
    {
        $coId = Coroutine::getPId();
        if (!isset(self::$coInstances[$coId])) {
            self::$coInstances[$coId] = new static(...$args);
            defer(function () use ($coId) {
                unset(self::$coInstances[$coId]);
            });
        }
        return self::$coInstances[$coId];
    }

    /**
     * @param mixed ...$args
     * @return mixed
     * @desc 按tag获取单例, 可用于static替换
     */
    public static function getInstanceByTag(...$args)
    {
        $tag = $args[0];
        if (!isset(self::$coInstances[$tag])) {
            self::$coInstances[$tag] = new static(...$args);
        }
        return self::$coInstances[$tag];
    }
}