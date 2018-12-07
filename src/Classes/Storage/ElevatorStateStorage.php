<?php declare(strict_types=1);

namespace Elevator\Classes\Storage;

use Elevator\Classes\Contracts\ElevatorStateStorageInterface;

/**
 * Class ElevatorStateStorage
 * @package Elevator\Classes\Storage
 */
class ElevatorStateStorage extends RedisStorage implements ElevatorStateStorageInterface
{
    const KEY = "elevator:state";

    /**
     * @param $state
     * @return bool
     */
    public function set($state)
    {
        return $this->redis->set(static::KEY, $state);
    }

    public function get(): ?string
    {
        return $this->redis->get(static::KEY);
    }
}