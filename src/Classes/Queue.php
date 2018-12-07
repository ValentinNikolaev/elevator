<?php declare(strict_types=1);

namespace Elevator\Classes;

use Elevator\Classes\Contracts\QueueInterface;
use Elevator\Classes\Contracts\QueueStorageInterface;

class Queue implements QueueInterface
{
    /**
     * @var QueueStorageInterface
     */
    private $storage;

    /**
     * Queue constructor.
     * @param QueueStorageInterface $storage
     */
    public function __construct(QueueStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param $floor
     * @return bool
     */
    public function addItem($floor)
    {
        return $this->storage->push($floor);
    }

    /**
     * @return array
     */
    public function list(): array
    {
        return $this->storage->list() ?? [];
    }

    /**
     * @param $floor
     * @return bool
     */
    public function removeItem($floor)
    {
        return $this->storage->remove($floor);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return "Queue: " . implode(", ", $this->list());
    }
}