<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Enums\Language;
use App\Enums\Page;
use App\Enums\Product;
use App\Framework\Translator;
use App\Services\Interfaces\IBusinessDetailsService;

final class PageViewModelFactory
{
    public function __construct(
        private readonly Translator $translator,
        private readonly IBusinessDetailsService $businessDetails,
        private readonly string $baseUrl,
        private readonly bool $contentApproved,
        private readonly bool $isDevelopment,
        private readonly ProductLinks $products,
    ) {
    }

    public function create(Language $language, Page $page): PageViewModel
    {
        $indexable = $page->isIndexable();

        return new PageViewModel(
            language: $language,
            title: $this->translator->text($language, 'meta.' . $page->value . '.title'),
            description: $this->translator->text($language, 'meta.' . $page->value . '.description'),
            robots: $indexable && $this->contentApproved ? 'index,follow' : 'noindex,follow',
            canonicalUrl: $indexable ? $this->baseUrl . $page->path($language) : null,
            alternateUrls: $indexable ? [
                'nl' => $this->baseUrl . $page->path(Language::Dutch),
                'en' => $this->baseUrl . $page->path(Language::English),
                'x-default' => $this->baseUrl . $page->path(Language::DEFAULT),
            ] : [],
            navigation: $this->navigation($language, $page),
            languageSwitchUrl: $page->path($language->other()),
            otherLanguage: $language->other(),
            business: $this->businessDetails->get(),
            showPreviewNotice: !$this->contentApproved,
            showScreenshotPlaceholders: $this->isDevelopment,
        );
    }

    /**
     * Main menu: the four products in their fixed order, then Portfolio, About and Contact.
     *
     * @return list<NavigationItem>
     */
    private function navigation(Language $language, Page $current): array
    {
        $items = [];
        foreach (Product::ordered() as $product) {
            $items[] = new NavigationItem(
                $this->translator->text($language, 'nav.product.' . $product->value),
                $this->products->url($product, $language),
                $product->page() === $current,
                $product->isExternal(),
            );
        }
        foreach (Page::navigation() as $item) {
            $items[] = new NavigationItem($this->translator->text($language, 'nav.' . $item->value), $item->path($language), $item === $current);
        }

        return $items;
    }
}
