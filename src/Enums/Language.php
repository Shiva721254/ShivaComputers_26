<?php

declare(strict_types=1);

namespace App\Enums;

enum Language: string
{
    case Dutch = 'nl';
    case English = 'en';

    public const DEFAULT = self::Dutch;

    public function other(): self
    {
        return $this === self::Dutch ? self::English : self::Dutch;
    }

    /** Picks the language for a page without a route, such as a 404. */
    public static function fromPath(string $path): self
    {
        return preg_match('#^/en(/|$)#D', $path) === 1 ? self::English : self::DEFAULT;
    }
}
