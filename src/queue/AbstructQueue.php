<?php

/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2019/3/22
 * @since          1.0
 */
namespace min\queue;

abstract class AbstructQueue implements QueueInterface
{
    /**
     * 连接消息队列
     * @return mixed
     */
    public function connection() {

    }

    /**
     * 入队列
     * @return mixed
     */
    public function push($topic, $job) {

    }

    /**
     * 出队列
     * @param $topic
     * @return mixed
     */
    public function pop($topic) {

    }

    /**
     * topic 消息长度
     * @param $topic
     * @return int
     */
    public function len($topic) : int {

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
    public function isConnected() : bool {

    }
}