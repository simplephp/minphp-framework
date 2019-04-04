<?php

/**
 * description     对象基础类
 * @author         kevin <askyiwang@gmail.com>
 * @date           2018/6/28
 * @since          1.0
 */

namespace min\base;

class BaseObject
{
    /**
     * 初始化配置文件
     * BaseObject constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // 执行构造事件
        if (!empty($config)) {
            \Min::configure($this, $config);
        }
        // 执行初始化事件
        $this->_onInitialize();
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        $this->_onDestruct();
    }

    /**
     * 构造事件
     */
    public function _onConstruct() {}

    /**
     * 初始化事件
     */
    public function _onInitialize() {}

    /**
     * 析构事件
     */
    public function _onDestruct() {}
}
