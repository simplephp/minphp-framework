<?php

/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2019/3/22
 * @since          1.0
 */
namespace min\pool;
use min\base\Singleton;
use Swoole\Coroutine;

class Context
{
    use Singleton;
    /**
     * @var array context pool
     */
    private $pool = [];

    public function get()
    {
        $id = Coroutine::getPid();
        if (isset($this->pool[$id])) {
            return $this->pool[$id];
        }
        return null;
    }
    /**
     * @desc 清除context
     */
    public function release()
    {
        $id = Coroutine::getPid();
        if (isset($this->pool[$id])) {
            unset($this->pool[$id]);
            Coroutine::clear($id);
        }
    }
    /**
     * @param $context
     * @desc 设置context
     */
    public function put($context)
    {
        $id = Coroutine::getPid();
        $this->pool[$id] = $context;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return count($this->pool);
    }
}