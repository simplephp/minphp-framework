<?php

/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2018/6/28
 * @since          1.0
 */

namespace min\base;

use min\di\Container;
use min\di\ServiceLocator;
use min\exception\NotFoundException;

/**
 * @property \min\console\Input $input
 * Class Application
 * @package min\base
 */
abstract class Application extends ServiceLocator
{
    /**
     * @var
     */
    private $_runtimePath;

    /**
     * Application constructor.
     */
    public function __construct($config = [])
    {
        \Min::$_app = $this;
        \Min::$container = new Container();
        //$this->registerErrorHandler($config);
        // 初始化组件
        $this->parseConfig($config);
        // 组件注入
        parent::__construct($config);

    }

    /**
     * 初始化
     * @param array $config
     */
    public function parseConfig(array &$config)
    {

        if (isset($config['runtimePath'])) {
            $this->setRuntimePath($config['runtimePath']);
            unset($config['runtimePath']);
        } else {
            $this->getRuntimePath();
        }

        // merge core components with custom components
        foreach ($this->baseComponents() as $id => $component) {
            if (!isset($config['components'][$id])) {
                $config['components'][$id] = $component;
            } elseif (is_array($config['components'][$id]) && !isset($config['components'][$id]['class'])) {
                $config['components'][$id]['class'] = $component['class'];
            }
        }
    }

    /**
     *
     * @param $command
     * @param $params
     */
    public function runAction($controller, $action, $params)
    {

        $controller = ucfirst($controller);

        if (!preg_match('/^[A-Za-z](\w|\.)*$/', $controller)) {
            throw new NotFoundException('wrong controller format:' . $controller);
        }

        if (!preg_match('/^[A-Za-z](\w|\.)*$/', $action)) {
            throw new NotFoundException('wrong action format:' . $action);
        }

        $classNamespace = $this->commandNamespace . '\\' . ucfirst($controller) . 'Command';

        $actionName = $action . 'Action';

        if (class_exists($classNamespace)) {

            $reflect = new \ReflectionClass($classNamespace);
            //$constructor = $reflect->getConstructor();
            $commandInstance = $reflect->newInstanceArgs($params);

            if (!is_callable([$commandInstance, $actionName])) {
                throw new NotFoundException('The action does not callable:' . $actionName);

            }
            // 执行操作方法
            $reflect = new \ReflectionMethod($commandInstance, $actionName);
            return $reflect->invokeArgs($commandInstance, $params);

        } else {
            throw new NotFoundException('The controller does not exist:' . $controller);
        }
    }

    /**
     * 初始化配置、基础组件 等
     */
    public function _onInitialize()
    {

    }

    /**
     * 命令行输入组件
     * @return mixed|null
     */
    public function getInput()
    {
        return $this->get('input');
    }


    /**
     * 命令行输入组件
     * @return mixed|null
     */
    public function getResponse()
    {
        return $this->get('response');
    }

    /**
     * 设置 runtime path
     * @param $path
     */
    public function setRuntimePath($path)
    {
        $this->_runtimePath = $path;
    }

    /**
     * 获取 runtime path
     * @return mixed
     */
    public function getRuntimePath()
    {
        return $this->_runtimePath;
    }


    /**
     * 基础组件
     * @return array
     */
    public function baseComponents()
    {
        return [
            'input' => ['class' => 'min\base\Input'],
            'response' => ['class' => 'min\base\Response'],
        ];
    }
}
