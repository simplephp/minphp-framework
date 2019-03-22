<?php
/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2019/3/22
 * @since          1.0
 */

namespace min\queue;


class RedisQueue extends AbstructQueue
{

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