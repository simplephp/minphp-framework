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

class BaseProcess extends Component
{
    public $processName;

    public $workProcess = 1;

    public $maxExecutions = 1000;

    public function start()
    {
        ProcessHelper::setTitle($this->processName . "-Master");
        $this->processBuilder();
    }


    // 创建进程
    protected function processBuilder()
    {
        for ($i = 0; $i < $this->workProcess; $i++) {
            $this->workProcessBuilder($i);
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
        $process   = new \Swoole\Process(function ($worker) use ($masterPid, $workerId) {
            try {

                ProcessHelper::setTitle($this->processName.'-wrok-'.$workerId);
                // 创建工作者
                $centerWorker = new CenterWorker([
                    'worker'      => $worker,
                    'inputQueue'  => $this->_inputQueue,
                    'outputQueue' => $this->_outputQueue,
                    'table'       => $this->_table,
                    'masterPid'   => $masterPid,
                    'workerId'    => $workerId,
                    'workerPid'   => $worker->pid,
                ]);
                // 执行回调
                isset($this->_onCenterStart) and call_user_func($this->_onCenterStart, $centerWorker);
                // 循环执行任务
                for ($j = 0; $j < $this->maxExecutions; $j++) {
                    $data = $centerWorker->inputQueue->pop();
                    if (empty($data)) {
                        continue;
                    }
                    try {
                        // 执行回调
                        call_user_func($this->_onCenterMessage, $centerWorker, $data);
                    } catch (\Throwable $e) {
                        // 回退数据到消息队列
                        $centerWorker->inputQueue->push($data);
                        // 休息一会，避免 CPU 出现 100%
                        sleep(1);
                        // 抛出错误
                        throw $e;
                    }
                }
            } catch (\Throwable $e) {
                \Mix::app()->error->handleException($e);
            }
        }, false, false);
        return $process;
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

}