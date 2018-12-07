<?php declare(strict_types=1);

namespace Elevator\Classes;

class Elevator
{
    /**
     * @var State
     */
    protected $state;

    /**
     * @var StateFloor
     */
    private $stateFloor;

    /**
     * @var StateDirection
     */
    private $stateDirection;

    public function __construct()
    {
        $this->state = elevatorState();
        $this->stateFloor = elevatorStateFloor();
        $this->stateDirection = elevatorStateDirection();
    }

    /**
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return StateFloor
     */
    public function getFloor()
    {
        return $this->stateFloor;
    }

    /**
     * @return StateDirection
     */
    public function getDirection()
    {
        return $this->stateDirection;
    }

    /**
     * @return $this
     */
    public function resetStateWithDirection() {
        $this->getDirection()->set(StateDirection::STATE_NOTHING);
        $this->getState()->set(State::STATE_STANDING);
        return $this;
    }

    public function __toString()
    {
        return 'State: '.$this->getState()->getDescription().'; '
           .'Direction: '.$this->getDirection()->getDescription().'; '
           .'Floor: '.$this->getFloor();
    }
}