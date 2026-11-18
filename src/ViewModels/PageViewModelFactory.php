<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Enums\Language;
use App\Enums\Page;
use App\Framework\Translator;
use App\Models\BusinessDetails;
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
        $business = $this->businessDetails->get();

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
            business: $business,
            showPreviewNotice: !$this->contentApproved,
            showScreenshotPlaceholders: $this->isDevelopment,
            structuredData: $page === Page::Home ? $this->organisationData($language, $business) : null,
        );
    }

    /** schema.org data that tells search engines who the business is (JSON-LD). */
    private function organisationData(Language $language, BusinessDetails $business): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            'name' => $business->name,
            'url' => $this->baseUrl . Page::Home->path($language),
            'description' => $this->translator->text($language, 'meta.home.description'),
            'logo' => $this->baseUrl . '/assets/img/favicon.svg',
            'areaServed' => 'NL',
        ];
        if ($business->email) {
            $data['email'] = $business->email;
        }
        if ($business->phone) {
            $data['telephone'] = str_replace(' ', '', $business->phone);
        }
        if ($business->vat) {
            $data['vatID'] = $business->vat;
        }
        // The address is stored as "Street 1, 1234 AB City".
        if ($business->address && preg_match('/^(.+),\s*(\d{4}\s?[A-Z]{2})\s+(.+)$/', $business->address, $m) === 1) {
            $data['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $m[1],
                'postalCode' => $m[2],
                'addressLocality' => $m[3],
                'addressCountry' => 'NL',
            ];
        }

        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_THROW_ON_ERROR);
    }
}
