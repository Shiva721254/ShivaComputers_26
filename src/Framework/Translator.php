<?php

declare(strict_types=1);

namespace App\Framework;

use App\Enums\Language;
use RuntimeException;

final class Translator
{
    /** @var array<string, array<string, mixed>> */
    private array $dictionaries = [];

    public function __construct(private readonly string $directory)
    {
    }

    public function text(Language $language, string $key): string
    {
        $value = $this->dictionary($language)[$key] ?? null;
        if (!is_string($value)) {
            throw new RuntimeException("Missing translation '{$key}' for '{$language->value}'.");
        }

        return $value;
    }

    /** @return list<array<string, string>> */
    public function items(Language $language, string $key): array
    {
        $value = $this->dictionary($language)[$key] ?? null;
        if (!is_array($value)) {
            throw new RuntimeException("Missing translation list '{$key}' for '{$language->value}'.");
        }

        return $value;
    }

    /** @return array<string, mixed> */
    private function dictionary(Language $language): array
    {
        return $this->dictionaries[$language->value] ??= require $this->directory . '/' . $language->value . '.php';
    }
}
