<?php declare(strict_types=1);

namespace Elevator\Command;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CallCommand
 * @package Elevator\Command
 */
class CallCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('elevator:call')
            ->setDescription('Perform elevator call')
            ->addArgument(
                'from',
                InputArgument::REQUIRED,
                'Floor called from'
            )
            ->addArgument(
                'to',
                InputArgument::REQUIRED,
                'To destination'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $from = (int)$input->getArgument('from');
            $to = (int)$input->getArgument('to');
            $this->checkArgument($from, $to);

            queue()->addItem(
                queueHelper()->preparePairItem($from, $to)
            );

            logger()->info('Success! Current pairs list: ' . queue()->__toString());
        } catch (Exception $e) {
            logger()->error($e->getMessage(), [
                'scope' => get_class($this),
                'class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    /**
     * @param mixed ...$argument
     */
    private function checkArgument(...$argument)
    {
        foreach ($argument as $argument) {
            if (!$argument || $argument > config('elevator.floorsCount')) {
                throw new InvalidArgumentException("Floor '$argument' can not be 0 or more then total floors count.");
            }
        }
    }
}
