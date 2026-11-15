<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Enums\Language;
use App\Enums\Page;
use App\Enums\Product;
use App\Framework\Translator;

/**
 * Resolves where each product lives, in the visitor's language:
 *   Software       → /{lang}/services
 *   Rescue OS      → https://rescue.shivacomputers.nl/{lang}
 *   Shiva Assist   → https://assist.shivacomputers.nl/{lang}
 *   Remote support → /{lang}/remote-support
 */
final class ProductLinks
{
    /** @param array<string, string> $siteUrls product value => base URL of its subdomain, e.g. 'rescue' => 'https://rescue.shivacomputers.nl' */
    public function __construct(
        private readonly Translator $translator,
        private readonly array $siteUrls,
        private readonly bool $allowLocalHttp = false,
    ) {
        foreach (Product::ordered() as $product) {
            if ($product->isExternal() && !self::isSafeSiteUrl($this->siteUrls[$product->value] ?? '', $this->allowLocalHttp)) {
                throw new \InvalidArgumentException(sprintf('Missing or unsafe site URL for product "%s"', $product->value));
            }
        }
    }

    public function url(Product $product, Language $language): string
    {
        $page = $product->page();

        return $page instanceof Page
            ? $page->path($language)
            : rtrim($this->siteUrls[$product->value], '/') . '/' . $language->value;
    }

    /** @return list<ProductCard> */
    public function cards(Language $language): array
    {
        return array_map(
            fn (Product $product): ProductCard => new ProductCard(
                $product,
                $this->text($language, $product, 'tag'),
                $this->text($language, $product, 'title'),
                $this->text($language, $product, 'text'),
                $this->text($language, $product, 'link'),
                $this->url($product, $language),
                $product->isExternal(),
            ),
            Product::ordered(),
        );
    }

    /**
     * Only an absolute https address without path, query, fragment or credentials, so a wrong setting
     * can never turn the menu into a link to somewhere unexpected. http://localhost only in development.
     */
    public static function isSafeSiteUrl(string $url, bool $allowLocalHttp = false): bool
    {
        $parts = parse_url($url);
        if ($parts === false || !isset($parts['scheme'], $parts['host'])
            || isset($parts['query']) || isset($parts['fragment']) || isset($parts['user']) || isset($parts['pass'])) {
            return false;
        }
        if (($parts['path'] ?? '') !== '' && $parts['path'] !== '/') {
            return false;
        }

        return $parts['scheme'] === 'https' || ($allowLocalHttp && $parts['scheme'] === 'http' && $parts['host'] === 'localhost');
    }

    private function text(Language $language, Product $product, string $field): string
    {
        return $this->translator->text($language, 'products.' . $product->value . '.' . $field);
    }
}
