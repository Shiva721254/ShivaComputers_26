<?php

declare(strict_types=1);

namespace App\Framework;

final readonly class Request
{
    public function __construct(
        public string $method,
        public string $path,
    ) {
    }

    public static function fromGlobals(): self
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        return new self($_SERVER['REQUEST_METHOD'] ?? 'GET', is_string($path) ? $path : '/invalid');
    }
}
