<?php

declare(strict_types=1);

namespace App\Framework;

final readonly class Request
{
    /**
     * @param array<string, mixed> $post  Submitted form fields
     * @param array<string, mixed> $query Query string values
     */
    public function __construct(
        public string $method,
        public string $path,
        public array $post = [],
        public array $query = [],
        public string $ip = '',
    ) {
    }

    public static function fromGlobals(): self
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        return new self(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            is_string($path) ? $path : '/invalid',
            $_POST,
            $_GET,
            (string) ($_SERVER['REMOTE_ADDR'] ?? ''),
        );
    }

    /** Reads a submitted text field, or an empty string when it is missing or not text. */
    public function input(string $name): string
    {
        $value = $this->post[$name] ?? '';

        return is_string($value) ? trim($value) : '';
    }
}
