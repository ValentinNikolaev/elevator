<?php declare(strict_types=1);

namespace Elevator\Classes\Contracts;

/**
 * Interface QueueStorageInterface
 * @package Elevator\Classes\Contracts
 */
interface QueueStorageInterface
{
    public function push($floor): int;

    public function list(): array;

    public function remove($floor): int;
}