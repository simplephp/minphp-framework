<?php

/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2019/3/22
 * @since          1.0
 */

namespace min\process;

use min\base\Component;
use min\helpers\ProcessHelper;
use Swoole\Table;

class BaseProcess
{
    public $processName;

    public $workProcess = 100;

    public $maxExecutions = 1000;

    public $_workerIntance = null;

    public $_processPool = [];

    public $_isModDaemon = false;

    public function start()
    {
        ProcessHelper::setTitle($this->processName . "-Master");
        $this->processBuilder();
        $this->signalHandle();

    }

    /**
     * 创建进程
     */
    protected function processBuilder()
    {
        for ($i = 0; $i < $this->workProcess; $i++) {
            $this->workProcessBuilder($i);
        }
    }

    /**
     * 绑定事件回调函数
     * @param $event
     * @param callable $callback
     */
    public function on($event, callable $callback)
    {
        switch ($event) {
            case 'Worker':
                $this->_workerIntance = $callback;
                break;
        }
    }

    /**
     * work 进程创建
     * @param $workerId
     * @return \Swoole\Process
     */
    protected function workProcessBuilder($workerId)
    {
        $masterPid = ProcessHelper::getPid();
        $process = new \Swoole\Process(function ($worker) use ($masterPid, $workerId) {
            try {

                ProcessHelper::setTitle($this->processName . '-wrok-' . $workerId);

                // 循环执行任务
                for ($j = 0; $j < $this->maxExecutions; $j++) {
                    $data = ['request_url' => 'www.baidu.com'];
                    if (empty($data)) {
                        continue;
                    }
                    try {
                        // 执行回调
                        call_user_func($this->_workerIntance, $data);
                    } catch (\Throwable $e) {
                        // 回退数据到消息队列
                        // 休息一会，避免 CPU 出现 100%
                        sleep(1);
                        // 抛出错误
                        throw $e;
                    }
                }
            } catch (\Throwable $e) {
                //\Mix::app()->error->handleException($e);
            }
        }, false, false);
        // 启动
        $pid = $process->start();
        // 保存实例
        $this->_processPool[$pid] = $workerId;
    }

    /**
     * 设置perocess 名称
     * @param string $processName
     */
    public function setProcessName(string $processName)
    {
        $this->processName = $processName;
    }

    /**
     * 创建 Table 共享
     */
    public function createTable()
    {
        $table = new Table(1024);
        $table->column('signal', Table::TYPE_INT, 1);
        $table->create();
    }


    /**
     * 基础信号处理
     */
    public function signalHandle()
    {
        // 子进程终止信号处理
        $this->subprocessExitSignalHandle();
        // 重启信号处理
        $this->restartSignalHandle();
        // 停止信号处理
        $this->stopSignalHandle();
    }

    /**
     * 子进程终止信号处理
     */
    protected function subprocessExitSignalHandle()
    {
        // 重建子进程
        \Swoole\Process::signal(SIGCHLD, function ($signal) {
            while ($result = \swoole_process::wait(false)) {
                $workerPid = $result['pid'];
                $this->rebootProcess($workerPid);
            }
        });
    }

    /**
     * 重启信号处理
     */
    protected function restartSignalHandle()
    {
        // 非守护执行模式下不处理该信号
        if (!$this->_isModDaemon) {
            return;
        }
        // 平滑重启
        \Swoole\Process::signal(SIGUSR1, function ($signal) {
            static $handled = false;
            // 防止重复调用
            if ($handled) {
                return;
            }
            $handled = true;
            // 修改信号
            //$this->_table->set('signal', ['value' => self::SIGNAL_RESTART]);
            // 定时处理
            swoole_timer_tick(1000, function () {
                static $tickCount = 0;
                $processPool = $this->_processPool;
                // 退出主进程
                if (empty($processPool) || $tickCount++ == 1) {
                    exit;
                }
                // PUSH空数据解锁阻塞进程
                $processTypes = array_column(array_values($processPool), 0);

            });
        });
    }

    /**
     * 停止信号处理
     */
    protected function stopSignalHandle()
    {
        // 停止
        \Swoole\Process::signal(SIGTERM, function ($signal) {
            static $handled = false;
            // 防止重复调用
            if ($handled) {
                return;
            }
            $handled = true;
            // 守护模式下修改信号
            if ($this->_isModDaemon) {
              //  $this->_table->set('signal', ['value' => self::SIGNAL_STOP_LEFT]);
            }
            // 定时处理
            swoole_timer_tick(1000, function () {
                $processPool = $this->_processPool;
                // 退出主进程
                if (empty($processPool)) {
                    exit;
                }
                // 左进程是否停止完成
                $processTypes = array_column(array_values($processPool), 0);
            });
        });
    }

    /**
     * 重启进程
     * @param $workerPid
     * @throws \min\exception\ProcessException
     */
    protected function rebootProcess($workerPid)
    {
        // 取出进程信息
        if (!isset($this->_processPool[$workerPid])) {
            throw new \min\exception\ProcessException('RebootProcess Error: no pid.');
        }

        $workerId = $this->_processPool[$workerPid];
        // 删除旧引用
        unset($this->_processPool[$workerPid]);
        // 根据信号判断是否不重建进程
        $this->workProcessBuilder($workerId);
    }
}