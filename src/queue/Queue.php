<?php
/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2019/3/22
 * @since          1.0
 */

namespace min\queue;

use min\base\Singleton;

class Queue extends AbstructQueue
{
    use Singleton;

    private $supportMap = [
        'redis',
        'rabbitmq',
        'mysql',
    ];

    public function build($queueType, $topic) {

        if(in_array($queueType, $this->supportMap)) {
            return false;
        }

    }
    /**
     * 连接消息队列
     * @return mixed
     */
    public function connection() {
        return 1;
    }

    /**
     * 入队列
     * @return mixed
     */
    public function push($topic, $job) {
        return 1;
    }

    /**
     * 出队列
     * @param $topic
     * @return mixed
     */
    public function pop($topic) {
        return 1;
    }

    /**
     * topic 消息长度
     * @param $topic
     * @return int
     */
    public function len($topic) : int {
        return 1;
    }

    /**
     * free 释放
     * @return mixed
     */
    public function close() {

    }

    /**
     * 是否连接
     * @return bool
     */
    public function isConnected() : bool  {
        return true;
    }
}