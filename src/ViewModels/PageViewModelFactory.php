<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Enums\Language;
use App\Enums\Page;
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
            navigation: array_map(
                fn (Page $item): NavigationItem => new NavigationItem(
                    $this->translator->text($language, 'nav.' . $item->value),
                    $item->path($language),
                    $item === $page,
                ),
                Page::navigation(),
            ),
            languageSwitchUrl: $page->path($language->other()),
            otherLanguage: $language->other(),
            business: $this->businessDetails->get(),
            showPreviewNotice: !$this->contentApproved,
            showScreenshotPlaceholders: $this->isDevelopment,
        );
    }
}
