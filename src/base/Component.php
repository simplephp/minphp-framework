<?php

/**
 * description     组件 yii2
 * @author         kevin <askyiwang@gmail.com>
 * @date           2018/6/28
 * @since          1.0
 */

namespace min\base;
use Closure;

class Component extends BaseObject
{
    /**
     * @var array shared component instances indexed by their IDs
     */
    private $_components = [];
    /**
     * @var array component definitions indexed by their IDs
     */
    private $_definitions = [];

    /**
     * @param $id
     * @param bool $throwException
     * @return mixed|null
     * @throws \Exception
     */
    public function get($id, $throwException = true)
    {
        if (isset($this->_components[$id])) {
            return $this->_components[$id];
        }

        if (isset($this->_definitions[$id])) {
            $definition = $this->_definitions[$id];
            if (is_object($definition) && !$definition instanceof Closure) {
                return $this->_components[$id] = $definition;
            }

            return $this->_components[$id] = \Min::createObject($definition);
        } elseif ($throwException) {
            throw new \Exception("Unknown component ID: $id");
        }
        return null;
    }

}
