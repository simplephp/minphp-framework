<?php
/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2019/3/7
 * @since          1.0
 */

namespace min\helpers;


use min\di\Instance;

class Test
{

    public $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function show() {

    }

    public function hidden() {

    }

}