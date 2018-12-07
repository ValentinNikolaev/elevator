<?php declare(strict_types=1);

namespace Elevator\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StatusCommand
 * @package Elevator\Command
 */
class StatusCommand extends Command
{
    const LOOP_MAX = 1000;

    protected function configure()
    {
        $this
            ->setDescription('Elevator status')
            ->setName('elevator:status');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $loopCount = 0;

        try {
            while ($loopCount < self::LOOP_MAX) {
                $loopCount++;

                $output->write('State: ' . elevator()->getState()->getDescription() . PHP_EOL);
                $output->write('Direction: ' . elevator()->getDirection()->getDescription() . PHP_EOL);
                $output->write('Floor: ' . elevator()->getFloor() .'/'.config('elevator.floorsCount') . PHP_EOL);
                $output->write('Pairs queues (' . count(queue()->list()) . '): ' . queue() . PHP_EOL);
                $output->write('======='. PHP_EOL);

                sleep(5);
            }
        } catch (Exception $e) {
            logger()->error($e->getMessage(), [
                'scope' => get_class($this),
                'class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}
