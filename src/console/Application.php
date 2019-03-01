<?php

namespace min\console;

/**
 * App类
 * @author 刘健 <coder.liu@qq.com>
 */
class Application
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

    }
}
