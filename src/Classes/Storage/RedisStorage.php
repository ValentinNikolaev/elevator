<?php declare(strict_types=1);

namespace Elevator\Classes\Storage;

/**
 * Class RedisStorage
 * @package Elevator\Classes\Storage
 */
class RedisStorage
{
    /**
     * @var \Predis\Client
     */
    protected $redis;

    public function __construct()
    {
        $this->redis = redis();
    }
}