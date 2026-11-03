<?php

declare(strict_types=1);

namespace App\Models;

/** A service that Shiva Computers offers, such as "Professional websites". */
final readonly class Offering
{
    public function __construct(
        public string $title,
        public string $description,
    ) {
    }
}
