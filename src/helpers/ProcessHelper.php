<?php

/**
 * description     进程帮助类
 * @author         kevin <askyiwang@gmail.com>
 * @date           2019/3/22
 * @since          1.0
 */
namespace min\helpers;

class ProcessHelper
{

    /**
     * 蜕变为守护进程
     * @param bool $closeStandardInputOutput
     */
    public static function daemon(bool $closeStandardInputOutput = true)
    {
        \Swoole\Process::daemon(true, !$closeStandardInputOutput);
    }

    /**
     * 设置进程标题
     * @param $title
     * @return bool
     */
    public static function setTitle($title)
    {
        if (stripos(PHP_OS, 'Darwin') !== false) {
            return false;
        }
        if (!function_exists('cli_set_process_title')) {
            return false;
        }
        return cli_set_process_title($title);
    }

    /**
     * 进程状态
     * @param $pid
     * @return bool
     */
    public static function isRunning($pid)
    {
        return self::kill($pid, 0);
    }

    /**
     * kill 进程 或 发送信号
     * @param $pid
     * @param null $signal
     * @return bool
     */
    public static function kill($pid, $signal = null)
    {
        if (is_null($signal)) {
            return \Swoole\Process::kill($pid);
        }
        return \Swoole\Process::kill($pid, $signal);
    }

    /**
     * 获取进程ID
     * @return int
     */
    public static function getPid()
    {
        return getmypid();
    }

    /**
     * 写入 PID 文件
     * @param $pidFile
     * @return bool
     */
    public static function writePidFile($pidFile)
    {
        $pid = ProcessHelper::getPid();
        $ret = file_put_contents($pidFile, $pid, LOCK_EX);
        return $ret ? true : false;
    }

    /**
     * 读取 PID 文件
     * @param $pidFile
     * @return bool|string
     */
    public static function readPidFile($pidFile)
    {
        if (!file_exists($pidFile)) {
            return false;
        }
        $pid = file_get_contents($pidFile);
        if (self::isRunning($pid)) {
            return $pid;
        }
        return false;
    }

}
