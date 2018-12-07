<?php declare(strict_types=1);

namespace Elevator\Classes\Strategy;

use Elevator\Classes\Contracts\QueueInterface;
use Elevator\Classes\Elevator;
use Elevator\Classes\State;
use Elevator\Classes\StateDirection;
use Elevator\Helpers\queueHelper;

/**
 * Class UpStrategy
 * @package Elevator\Classes\Strategy
 * @todo Demetra?
 */
class UpStrategy
{
    public function process(Elevator $elevator)
    {
        $floor = $elevator->getFloor();
        $currentFloor = $floor->get();
        $maxFloor = queueHelper()->getMaxFloorFromPairs($currentFloor);
        $floorsLoop = range($currentFloor, $maxFloor);

        logger()->info('Floors to loop up: ' . json_encode($floorsLoop));

        foreach ($floorsLoop as $loopFloor) {
            $elevator->getFloor()->set($loopFloor);

            logger()->info('Floor: '.$elevator->getFloor()->get());
            if (queueHelper()->isNeedToOpenDoorForUpStrategy($loopFloor)) {
                logger()->info('STATE_USER_PROCESSING');
                $elevator->getState()->set(State::STATE_USER_PROCESSING);
                sleep(config("elevator.iterationFloorTime"));
            }
            $elevator->getState()->set(State::STATE_TRANSPORTING);
        }

        $elevator->resetStateWithDirection();
        logger()->info('After up strategy state: '. $elevator);

        return $elevator;
    }
}