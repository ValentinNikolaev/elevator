<?php declare(strict_types=1);

namespace Elevator\Classes\Contracts;

/**
 * Interface QueueInterface
 * @package Elevator\Classes\Contracts
 */
interface QueueInterface
{
    public function addItem($item);

    public function removeItem($item);

    public function list(): array;

    public function __toString(): string;

}