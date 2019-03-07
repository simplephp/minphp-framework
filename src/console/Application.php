<?php

/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2018/6/28
 * @since          1.0
 */
namespace min\console;

use min\base\Input;
use min\di\Container;

class Application extends \min\base\Application
{

    // 命令命名空间
    public $commandNamespace = '';

    // 命令
    public $commands = [];

    // 执行功能 (CLI模式)
    public function run()
    {
        if (PHP_SAPI != 'cli') {
            throw new \RuntimeException('Please run in CLI mode.');
        }
        //$input = $this->get('input');
        $Container = new Container();

        $args = ['hello', 'world'];
        $Container->set('myDB', [
            'class' => 'min\helpers\Test',
            'config' => ['root'],
            'password' => '',
            'charset' => 'utf8',
        ]);

        $Container->get('myDB');



    }
}
