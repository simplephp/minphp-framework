<?php

/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2019/3/22
 * @since          1.0
 */
interface MemoryInterface
{

    public function get(string $key);

    public function set(string $key, $value);


}