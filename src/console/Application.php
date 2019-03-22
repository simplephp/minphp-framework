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
use min\pool\Redis;

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

        $input = $this->getInput();
        $command = $input->getCommand();

        if (empty($command)) {
            throw new \min\exception\EmptyException("Please input command, '-h/--help' view help.");
        }

        if (in_array($input->getControllerName(), ['-h', '--help'])) {
            $this->help();
            return 0;
        }

        if (in_array($input->getControllerName(), ['-v', '--version'])) {
            $this->version();
            return 0;
        }

        $params = $input->getOptions();
        $result = $this->runAction($input->getControllerName(), $input->getActionName(), $params);

    }


    // 帮助
    protected function help()
    {
        $input = $input = $this->getInput();
        $output = $this->getResponse();
        $output->writeln("Usage: {$input->getScriptFileName()} [OPTIONS] [COMMAND [OPTIONS]]");
        $this->printOptions();
        $this->printCommands();
        $output->writeln('');
    }

    // 版本
    protected function version()
    {
        $input = $input = $this->getInput();
        $output = $this->getResponse();
        $version = \Min::VERSION;
        $output->writeln("MixPHP Framework Version {$version}");
    }

    // 打印选项列表
    protected function printOptions()
    {
        $output = $this->getResponse();
        $output->writeln('');
        $output->writeln('Options:');
        $output->writeln("  -h/--help\tPrint usage.");
        $output->writeln("  -v/--version\tPrint version information.");
    }

    // 打印命令列表
    protected function printCommands()
    {
        $output = $this->getResponse();
        $output->writeln('');
        $output->writeln('Commands:');
        $prevPrefix = '';
        foreach ($this->commands as $command => $item) {
            $prefix = explode(' ', $command)[0];
            if ($prefix != $prevPrefix) {
                $prevPrefix = $prefix;
                $output->writeln('  ' . $prefix);
            }
            $output->write(str_repeat(' ', 4) . $command, Output::FG_GREEN);
            $output->writeln((isset($item['description']) ? "\t{$item['description']}" : ''), Output::NONE);
        }
    }
}
