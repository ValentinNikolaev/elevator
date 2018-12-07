<?php declare(strict_types=1);

namespace Elevator\Classes;

use Elevator\Classes\Contracts\ElevatorStateStorageInterface;

class State
{
    const STATE_STANDING = 0;
    const STATE_TRANSPORTING = 1;
    const STATE_USER_PROCESSING = 10;
    const STATE_ALARM = 21;

    const STATE_TRANSLATE = [
        self::STATE_STANDING => 'Standing',
        self::STATE_USER_PROCESSING => 'Processing',
        self::STATE_TRANSPORTING => 'Transporting',
        self::STATE_ALARM => 'Alarm',
    ];

    private $supportedStates = [
        self::STATE_STANDING,
        self::STATE_USER_PROCESSING,
        self::STATE_TRANSPORTING,
        self::STATE_ALARM,
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
     * @return int|string|null
     */
    public function get()
    {
        $result = $this->storage->get();
        if (!$result) {
            $this->set(self::STATE_STANDING);
            return self::STATE_STANDING;
        }
        return $result;
    }

    /**
     * @param $state
     * @return mixed
     */
    public function set($state)
    {
        /**
         * @todo think about StateDirection;
         * @todo cache results
         */
        if (!in_array($state, $this->supportedStates)) {
            $state = self::STATE_ALARM;
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