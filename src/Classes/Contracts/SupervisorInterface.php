<?php declare(strict_types=1);

namespace Elevator\Classes\Contracts;

/**
 * Interface SupervisorInterface
 * @package Elevator\Contracts
 */
interface SupervisorInterface
{
    public function hasTasks() : bool;

    public function runTasks() : bool;
}