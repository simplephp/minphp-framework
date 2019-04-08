<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/3
 * Time: 下午1:21
 */

namespace min\pool;

use Swoole\Table;

class PoolManager
{
    use \min\base\Singleton;

    private $poolTable = null;
    private $poolClassList = [];
    private $poolObjectList = [];

    const TYPE_ONLY_WORKER = 1;
    const TYPE_ONLY_TASK_WORKER = 2;
    const TYPE_ALL_WORKER = 3;

    final public function __construct()
    {

    }

    function registerPool(string $class, $minNum, $maxNum, $type = self::TYPE_ONLY_WORKER)
    {
        try {
            $ref = new \ReflectionClass($class);
            if ($ref->isSubclassOf(Pool::class)) {
                $this->poolClassList[$class] = [
                    'min' => $minNum,
                    'max' => $maxNum,
                    'type' => $type
                ];
                return true;
            } else {
                Trigger::throwable(new \Exception($class . ' is not Pool class'));
            }
        } catch (\Throwable $throwable) {
            Trigger::throwable($throwable);
        }
        return false;
    }

    function getPool(string $class):?Pool
    {
        if (isset($this->poolObjectList[$class])) {
            return $this->poolObjectList[$class];
        } else {
            return null;
        }
    }


    function getPoolTable()
    {
        return $this->poolTable;
    }

    public static function generateTableKey(string $class, int $workerId): string
    {
        return substr(md5($class . $workerId), 8, 16);
    }

}