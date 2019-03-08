<?php

/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2018/6/28
 * @since          1.0
 */
namespace min\base;

use min\di\Container;

class Application extends Component
{

    // 初始化回调
    public $initialize = [];

    // 基础路径
    public $basePath = '';

    // 组件配置
    public $components = [];

    // 类库配置
    public $libraries = [];

    // 组件容器
    protected $_components;

    // 组件命名空间
    protected $_componentPrefix;

    /**
     * Application constructor.
     */
    public function __construct($config = [])
    {
        \Min::$_app = $this;
        \Min::$container = new Container();
        //$this->registerErrorHandler($config);

        // 组件注入
        Component::__construct($config);
    }


    public function getRequest()
    {
        return $this->get('request');
    }

}
