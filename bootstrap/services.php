<?php declare(strict_types = 1);

use Elevator\Classes\Elevator;
use Elevator\Classes\Queue;
use Elevator\Classes\State;
use Elevator\Classes\StateDirection;
use Elevator\Classes\StateFloor;
use Elevator\Classes\Storage\ElevatorStateDirectionStorage;
use Elevator\Classes\Storage\ElevatorStateFloorStorage;
use Elevator\Classes\Storage\ElevatorStateStorage;
use Elevator\Classes\Storage\QueuePairsStorage;
use Elevator\Classes\Supervisor;
use Elevator\Helpers\queueHelper;
use Monolog\Handler\StreamHandler;
use Phalcon\Di;
use Monolog\Logger;
use Phalcon\Events\Manager;
use Phalcon\Config\Adapter\Ini;

$container = new Di();

// Config service
$container->setShared('config', function () {
    $configFile = sprintf('/etc/%s/%s.ini', getenv('APPLICATION_NAME'), getenv('APPLICATION_NAME'));

    if (!file_exists($configFile)) {
        fwrite(STDERR, "Unable to read application config from: '{$configFile}'. Exit...\n");
        exit (1);
    }

    return new Ini($configFile);
});

// Logger service
$container->setShared('logger', function () {
    $channel = config('logs.channel');
    $logger = new Logger($channel);

    $handler = new StreamHandler('/var/log/elevator/'.$channel.'.log');

    $handler->pushProcessor(function ($record) {
        $record['extra']['service_version'] = config('application.version');

        return $record;
    });

    $handler->setLevel(config('logs.level'));

    $logger->pushHandler($handler);

    $logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

    return $logger;
});

$container->setShared('redis', function () {
    $client = new Predis\Client([
        'host'   => config('redis.host'),
        'host'   => config('redis.host'),
        'password'   => config('redis.password')
    ]);

    return $client;
});

$container->setShared('supervisor', Supervisor::class);
$container->setShared('queueHelper', QueueHelper::class);

$container->setShared('queue', function () {
   $queue = new Queue(
       new QueuePairsStorage()
   );

   return $queue;
});


$container->setShared('elevator', function () {
    return new Elevator();
});

$container->setShared('elevatorState', function () {
    $state = new State(
        new ElevatorStateStorage()
    );

    return $state;
});


$container->setShared('elevatorStateDirection', function () {
    $state = new StateDirection(
        new ElevatorStateDirectionStorage()
    );

    return $state;
});

$container->setShared('elevatorStateFloor', function () {
    $state = new StateFloor(
        new ElevatorStateFloorStorage(),
        (int) config('elevator.floorsCount')
    );

    return $state;
});


Di::setDefault($container);
