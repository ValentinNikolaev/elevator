<?php declare(strict_types=1);

namespace Elevator\Classes\Strategy;

use Elevator\Classes\Contracts\QueueInterface;
use Elevator\Classes\Elevator;
use Elevator\Classes\State;
use Elevator\Classes\StateDirection;

/**
 * Class StandingStrategy
 * @package Elevator\Classes\Strategy
 * @todo Demetra?
 */
class StandingStrategy
{
    public function process(Elevator $elevator): Elevator
    {
        $currentFloor = $elevator->getFloor()->get();
        $maxFloor = queueHelper()->getMaxFloorFromPairs($currentFloor);

        if ($elevator->getState()->get() == State::STATE_STANDING) {
            $elevator->getState()->set(State::STATE_TRANSPORTING);
            if ($currentFloor < $maxFloor) {
                $elevator->getDirection()->set(StateDirection::STATE_MOVING_UP);
            } else {
                $elevator->getDirection()->set(StateDirection::STATE_MOVING_DOWN);
            }
            logger()->info('Moving: '. $elevator->getDirection()->getDescription());
        }

        return $elevator;
    }
}