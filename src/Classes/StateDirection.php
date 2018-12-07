<?php declare(strict_types=1);

namespace Elevator\Classes;

use Elevator\Classes\Contracts\ElevatorStateStorageInterface;

class StateDirection
{
    const STATE_NOTHING = 0;
    const STATE_MOVING_UP = 1;
    const STATE_MOVING_DOWN = 2;

    private $supportedStates = [
        self::STATE_NOTHING,
        self::STATE_MOVING_UP,
        self::STATE_MOVING_DOWN,
    ];

    const STATE_TRANSLATE = [
        self::STATE_NOTHING => 'Nothing',
        self::STATE_MOVING_UP => 'Up',
        self::STATE_MOVING_DOWN => 'Down'
    ];

    /**
     * @var ElevatorStateStorageInterface
     */
    private $storage;

    /**
     * Queue constructor.
     * @param ElevatorStateStorageInterface $storage
     */
    public function __construct(ElevatorStateStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return int|null
     */
    public function get()
    {
        $result = $this->storage->get();
        if (!$result) {
            $this->set(self::STATE_NOTHING);
            return self::STATE_NOTHING;
        }
        return $result;
    }

    /**
     * @param $state
     * @return bool
     */
    public function set($state)
    {
        /**
         * @todo think about State;
         * @todo cache results
         */
        if (!in_array($state, $this->supportedStates)) {
            $state = self::STATE_NOTHING;
        }
        return $this->storage->set($state);
    }

    /**
     * @param string $state
     * @return mixed|string
     */
    public function getDescription($state = null) {
        $state = $state ?? $this->get();
        return self::STATE_TRANSLATE[$state] ?? 'Unknown';
    }

    public function __toString()
    {
        return (string)$this->get();
    }
}