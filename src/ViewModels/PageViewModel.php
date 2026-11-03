<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Enums\Language;
use App\Models\BusinessDetails;

/** Everything the shared layout needs, already prepared, so the layout holds no logic. */
final readonly class PageViewModel
{
    /**
     * @param array<string, string> $alternateUrls hreflang value => URL
     * @param list<NavigationItem> $navigation
     */
    public function __construct(
        public Language $language,
        public string $title,
        public string $description,
        public string $robots,
        public ?string $canonicalUrl,
        public array $alternateUrls,
        public array $navigation,
        public string $languageSwitchUrl,
        public Language $otherLanguage,
        public BusinessDetails $business,
        public bool $showPreviewNotice,
        public bool $showScreenshotPlaceholders,
    ) {
    }

    /** Builds a link inside the current language, such as /nl/contact. */
    public function url(string $suffix = ''): string
    {
        return '/' . $this->language->value . $suffix;
    }
}
