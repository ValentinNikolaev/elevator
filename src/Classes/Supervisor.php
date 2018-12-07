<?php declare(strict_types=1);

namespace Elevator\Classes;

use Elevator\Classes\Strategy\StandingStrategy;
use Elevator\Classes\Strategy\UpStrategy;
use Elevator\Classes\Strategy\DownStrategy;
use Elevator\Classes\Contracts\SupervisorInterface;

class Supervisor implements SupervisorInterface
{
    public function hasTasks(): bool
    {
        return !elevator()->getState() !== State::STATE_STANDING || $this->queueHaveNotEmptyLists();
    }

    /**
     * @todo strategies should be rewrite
     * @return bool
     */
    public function runTasks(): bool
    {
        if ($this->queueHaveNotEmptyLists()) {
            (new StandingStrategy())->process(elevator());

            if ((string)elevator()->getDirection() == StateDirection::STATE_MOVING_UP) {
                (new UpStrategy())->process(elevator());
            }

            if ((string)elevator()->getDirection() == StateDirection::STATE_MOVING_DOWN) {
                (new DownStrategy())->process(elevator());
            }
        }

        elevator()->resetStateWithDirection();

        return true;
    }

    /**
     * @return bool
     */
    private function queueHaveNotEmptyLists() {
        return queue()->list();
    }
}