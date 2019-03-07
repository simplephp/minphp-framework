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
    // 构造
    public function __construct($config = [])
    {
        // 执行构造事件
        $this->onConstruct();

        // 执行初始化事件
        $this->afterInitialize();
    }

    // 析构
    public function __destruct()
    {
        $this->onDestruct();
    }

    // 构造事件
    public function onConstruct()
    {
    }

    // 初始化事件
    public function afterInitialize()
    {
    }

    // 析构事件
    public function onDestruct()
    {
    }

}
