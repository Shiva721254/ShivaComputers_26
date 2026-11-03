<?php

declare(strict_types=1);

namespace App\ViewModels;

final readonly class NavigationItem
{
    public function __construct(
        public string $label,
        public string $url,
        public bool $isCurrent,
    ) {
    }
}
