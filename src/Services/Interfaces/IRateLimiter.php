<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface IRateLimiter
{
    /** Records an attempt and returns false when this visitor has made too many recently. */
    public function attempt(string $visitor): bool;
}
