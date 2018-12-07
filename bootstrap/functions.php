<?php declare(strict_types=1);

use Elevator\Classes\Contracts\QueueInterface;
use Elevator\Classes\Contracts\SupervisorInterface;
use Elevator\Classes\Elevator;
use Elevator\Classes\State as ElevatorState;
use Elevator\Classes\StateDirection;
use Elevator\Classes\StateFloor;
use Elevator\Helpers\QueueHelper;
use Phalcon\Di;
use Phalcon\Config;
use Monolog\Logger;
use Phalcon\DiInterface;

/**
 * This calls our default dependency injection.
 *
 * @param  mixed
 * @return mixed|DiInterface
 */
function container()
{
    $default = Di::getDefault();
    $args = func_get_args();

    if (!$default) {
        trigger_error('Unable to resolve Dependency Injection container.', E_USER_ERROR);
    }

    if (empty($args)) {
        return $default;
    }

    return call_user_func_array([$default, 'getShared'], $args);
}

/**
 * @return Logger
 */
function logger(): Logger
{
    return container(__FUNCTION__);
}

/**
 * @return SupervisorInterface
 */
function supervisor(): SupervisorInterface
{
    return container(__FUNCTION__);
}

/**
 * @return QueueHelper
 */
function queueHelper(): QueueHelper
{
    return container(__FUNCTION__);
}

/**
 * @return Predis\Client
 */
function redis(): Predis\Client
{
    return container(__FUNCTION__);
}

/**
 * @return QueueInterface
 */
function queue(): QueueInterface
{
    return container(__FUNCTION__);
}

/**
 * @return Elevator
 */
function elevator(): Elevator
{
    return container(__FUNCTION__);
}

/**
 * @return ElevatorState
 */
function elevatorState(): ElevatorState
{
    return container(__FUNCTION__);
}

/**
 * @return StateFloor
 */
function elevatorStateFloor(): StateFloor
{
    return container(__FUNCTION__);
}

/**
 * @return StateDirection
 */
function elevatorStateDirection(): StateDirection
{
    return container(__FUNCTION__);
}

/**
 * @return Config | mixed
 */
function config()
{
    if (!container()->has(__FUNCTION__)) {
        trigger_error('Unable to resolve Config object.', E_USER_ERROR);
    }

    $args = func_get_args();
    $config = container(__FUNCTION__);

    if (empty($args)) {
        return $config;
    }

    return call_user_func_array([$config, 'path'], $args);
}
