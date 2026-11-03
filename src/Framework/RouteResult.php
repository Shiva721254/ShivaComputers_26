<?php

declare(strict_types=1);

namespace App\Framework;

final readonly class RouteResult
{
    public const FOUND = 'found';
    public const NOT_FOUND = 'not_found';
    public const METHOD_NOT_ALLOWED = 'method_not_allowed';
    public const REDIRECT = 'redirect';

    /**
     * @param array{0: class-string, 1: string}|null $handler
     * @param array<string, string> $parameters
     * @param list<string> $allowedMethods
     */
    private function __construct(
        public string $type,
        public ?array $handler = null,
        public array $parameters = [],
        public array $allowedMethods = [],
        public ?string $location = null,
    ) {
    }

    public static function found(array $handler, array $parameters): self
    {
        return new self(self::FOUND, handler: $handler, parameters: $parameters);
    }

    public static function notFound(): self
    {
        return new self(self::NOT_FOUND);
    }

    public static function methodNotAllowed(array $allowedMethods): self
    {
        return new self(self::METHOD_NOT_ALLOWED, allowedMethods: $allowedMethods);
    }

    public static function redirect(string $location): self
    {
        return new self(self::REDIRECT, location: $location);
    }
}
