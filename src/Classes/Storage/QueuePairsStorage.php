<?php declare(strict_types=1);

namespace Elevator\Classes\Storage;

use Elevator\Classes\Contracts\QueueStorageInterface;

/**
 * Class QueuePairsStorage
 * @package Elevator\Classes\Storage
 */
class QueuePairsStorage extends RedisStorage implements QueueStorageInterface
{
    const KEY = "elevator:queue-pairs";

    public function push($value): int
    {
        return $this->redis->sadd(static::KEY, [$value]);
    }

    public function list(): array
    {
        return $this->redis->smembers(static::KEY);
    }

    public function remove($value): int
    {
        return $this->redis->srem(static::KEY, $value);
    }
}