<?php declare(strict_types=1);

namespace Elevator\Classes;

use Elevator\Classes\Contracts\ElevatorStateStorageInterface;

class StateFloor
{
    /**
     * @var ElevatorStateStorageInterface
     */
    private $storage;

    /**
     * Queue constructor.
     * @param ElevatorStateStorageInterface $storage
     * @param int $maxFloors
     */
    public function __construct(ElevatorStateStorageInterface $storage, int $maxFloors)
    {
        $this->storage = $storage;
        $this->maxFloors = $maxFloors;
    }

    /**
     * @return int|null
     */
    public function get()
    {
        $result = $this->storage->get();
        if (!$result) {
            $result = 1;
            $this->set(1);
        }
        return $result;
    }

    /**
     * @param $state
     * @return bool
     */
    public function set($state)
    {
        return $this->storage->set($state);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->get();
    }
}
