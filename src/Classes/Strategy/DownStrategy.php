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
class DownStrategy
{
    public function process(Elevator $elevator)
    {
        $floor = $elevator->getFloor();
        $currentFloor = $floor->get();


        $floorsLoop = array_reverse(range(queueHelper()->getMinFloorFromPairs($currentFloor), $currentFloor));

        logger()->info('Floors to loop: ' . json_encode($floorsLoop));

        foreach ($floorsLoop as $loopFloor) {
            $elevator->getFloor()->set($loopFloor);

            logger()->info('Floor: ' . $elevator->getFloor()->get());
            if (queueHelper()->isNeedToOpenDoorForDownStrategy($loopFloor)) {
                logger()->info('STATE_USER_PROCESSING');
                $elevator->getState()->set(State::STATE_USER_PROCESSING);
                sleep(config("elevator.iterationFloorTime"));
            }
            $elevator->getState()->set(State::STATE_TRANSPORTING);
        }


        $elevator->resetStateWithDirection();
        logger()->info('After down strategy state: ' . $elevator);

        return $elevator;
    }
}