<?php

declare(strict_types=1);

namespace App\Framework;

use RuntimeException;

/** A small dependency injection container: it builds each object once, on first use. */
final class Container
{
    /** @var array<string, callable(self): object> */
    private array $factories = [];

    /** @var array<string, object> */
    private array $instances = [];

    /** @param callable(self): object $factory */
    public function set(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }

    public function get(string $id): object
    {
        if (!isset($this->factories[$id])) {
            throw new RuntimeException("Nothing is registered for '{$id}'.");
        }

        return $this->instances[$id] ??= ($this->factories[$id])($this);
    }
}
