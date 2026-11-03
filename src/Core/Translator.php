<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Translator
{
    /** @var array<string, mixed> */
    private array $entries;

    public function __construct(string $directory, public readonly string $language)
    {
        $this->entries = require $directory . '/' . $language . '.php';
    }

    public function text(string $key): string
    {
        $value = $this->entries[$key] ?? null;
        if (!is_string($value)) {
            throw new RuntimeException("Missing translation '{$key}' for '{$this->language}'.");
        }

        return $value;
    }

    /** @return array<int, array<string, string>> */
    public function items(string $key): array
    {
        $value = $this->entries[$key] ?? null;
        if (!is_array($value)) {
            throw new RuntimeException("Missing translation list '{$key}' for '{$this->language}'.");
        }

        return $value;
    }
}
