<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Enums\Product;

/** One product on the home page, with all text already translated and the link already resolved. */
final readonly class ProductCard
{
    public function __construct(
        public Product $product,
        public string $tag,
        public string $title,
        public string $text,
        public string $linkText,
        public string $url,
        public bool $isExternal,
    ) {
    }
}
