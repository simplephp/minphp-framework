<?php

/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2018/6/28
 * @since          1.0
 */
namespace min\console;

use min\di\Container;
use min\exception\ExitException;

class Application extends \min\base\Application
{

    // 命令命名空间
    public $commandNamespace = '';

    // 命令
    public $commands = [];

    /**
     *
     */
    public function run()
    {
        if (PHP_SAPI != 'cli') {
            throw new \RuntimeException('Please run in CLI mode.');
        }

        \Min::$container->set('input', [
            'class' => 'min\console\Input',
        ]);

        $input = \Min::$container->get('input');


    }

    public function executeAction(string $route, $options) {

    }


}
