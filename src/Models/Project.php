<?php

declare(strict_types=1);

namespace App\Models;

final readonly class Project
{
    /** @param list<string> $stack */
    public function __construct(
        public string $name,
        public string $repositoryUrl,
        public array $stack,
        public bool $featured,
        public string $label,
        public string $description,
    ) {
    }
}
