<?php

/**
 * description
 * @author         kevin <askyiwang@gmail.com>
 * @date           2019/3/7
 * @since          1.0
 */
namespace min\exception;

class NotFoundException extends \Exception
{
    public function getName()
    {
        return 'Invalid Configuration';
    }
}