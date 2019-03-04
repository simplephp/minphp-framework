<?php

namespace min\base;

/**
 * 对象基类
 * @author 刘健 <coder.liu@qq.com>
 */
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
