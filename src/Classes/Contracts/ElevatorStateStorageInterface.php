<?php declare(strict_types=1);

namespace Elevator\Classes\Contracts;

/**
 * Interface ElevatorStateStorageInterface
 * @package Elevator\Classes\Contracts
 */
interface ElevatorStateStorageInterface
{
    public function get(): ?string;

    public function set($state);
}