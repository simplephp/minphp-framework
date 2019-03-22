<?php

/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2018/6/28
 * @since          1.0
 */
namespace min\base;

use app\commands\TestCommand;
use min\di\Container;

/**
 * @property \min\console\Input $input
 * Class Application
 * @package min\base
 */
class Application extends Component
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

        $this->preInit($config);
        // 组件注入
        Component::__construct($config);
    }

    /**
     *
     * @param $command
     * @param $params
     */
    public function runAction($controller, $action, $params) {


        $classNamespace = $this->commandNamespace.'\\'.ucfirst($controller).'Command';


        $actionName = $action.'Action';

        if (class_exists($classNamespace)) {
            $commandInstance = new $classNamespace($params);
            // 判断方法是否存在

            if (method_exists($commandInstance, $actionName)) {

                $status = $commandInstance->$actionName();
                var_dump($status);
            }
        } else {
            var_dump($classNamespace);
        }
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
     * 初始化
     * @param array $config
     */
    public function preInit(array &$config) {

        if (isset($config['runtimePath'])) {
            $this->setRuntimePath($config['runtimePath']);
            unset($config['runtimePath']);
        } else {
            // set "@runtime"
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
