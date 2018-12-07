<?php declare(strict_types = 1);

namespace Elevator\Command;

use Elevator\Classes\State;
use Elevator\Classes\StateDirection;
use Elevator\Traits\SignalTrait;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunSupervisorCommand
 * @package Elevator\Command
 */
class RunSupervisorCommand extends Command
{
    use SignalTrait;

    const LOOP_MAX = 10000;

    protected function configure()
    {
        $this
            ->setName('elevator:supervisor')
            ->setDescription('run elevator supervisor');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getSignalHelper()->listen();
        $loopCount = 0;
        logger()->info('Start supervisor' );
        try {
            while (!$this->wasReceivedStopSignal()) {
                // Restart script
                if ($loopCount > self::LOOP_MAX) {
                    break;
                }

                $loopCount++;

                if (supervisor()->hasTasks()) {
                    supervisor()->runTasks();
                }

                sleep(config('elevator-supervisor.iterationLookupTime'));
            }

            logger()->info('Got stop signal(s). Exit...' );
        } catch (Exception $e) {
            logger()->error($e->getMessage(), [
                'scope' => get_class($this),
                'class' => get_class($e),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            // Prevent too fast restart
            sleep(5);
        }
    }
}
