<?php

declare(strict_types=1);

namespace App\Enums;

enum Language: string
{
    case Dutch = 'nl';
    case English = 'en';

    /** English is the default for `/` and for pages outside a language prefix; Dutch is the alternative. */
    public const DEFAULT = self::English;

    public function other(): self
    {
        return $this === self::Dutch ? self::English : self::Dutch;
    }

    /** Picks the language for a page without a route, such as a 404. */
    public static function fromPath(string $path): self
    {
        return preg_match('#^/(nl|en)(/|$)#D', $path, $match) === 1 ? self::from($match[1]) : self::DEFAULT;
    }
}
