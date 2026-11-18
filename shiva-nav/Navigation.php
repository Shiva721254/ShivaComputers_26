<?php

declare(strict_types=1);

namespace ShivaNav;

/**
 * Turns menu.php into ready-to-render data for header.php, for one site and one language.
 *
 * Shared, identical copy in all three Shiva Computers sites (source of truth: ShivaComputers_26/shiva-nav/).
 * It has no dependencies on the site around it: each site passes in its own addresses, the current
 * page key and its language links.
 */
final class Navigation
{
    public const VERSION = '1.0.0';
    public const SITES = ['main', 'rescue', 'assist'];
    private const FALLBACK_LANGUAGE = 'en';

    /** @var array<string, mixed> */
    private readonly array $menu;

    /**
     * @param array<string, string> $siteUrls site => base address, e.g. 'main' => 'https://shivacomputers.nl'
     * @param string $currentSite the site rendering the menu; its own links stay relative
     */
    public function __construct(
        private readonly array $siteUrls,
        private readonly string $currentSite,
        bool $allowLocalHttp = false,
        ?array $menu = null,
    ) {
        if (!in_array($currentSite, self::SITES, true)) {
            throw new \InvalidArgumentException('Unknown site: ' . $currentSite);
        }
        foreach (self::SITES as $site) {
            if (!self::isSafeBaseUrl($siteUrls[$site] ?? '', $allowLocalHttp)) {
                throw new \InvalidArgumentException(sprintf('Missing or unsafe address for site "%s"', $site));
            }
        }
        $this->menu = $menu ?? require __DIR__ . '/menu.php';
    }

    /**
     * @param ?string $currentKey key of the page being shown ('services', 'assist', ...); null when none applies
     * @param list<array{code: string, name: string, title: string, url: string}> $languageLinks other languages of the current page
     * @return array<string, mixed>
     */
    public function build(string $language, ?string $currentKey, array $languageLinks): array
    {
        $items = [];
        foreach ($this->menu['items'] as $item) {
            if (isset($item['children'])) {
                $children = array_map(fn (array $child): array => $this->link($child, $language, $currentKey), $item['children']);
                $items[] = [
                    'key' => $item['key'],
                    'label' => $this->text($item['label'], $language),
                    'url' => null,
                    'current' => false,
                    'active' => in_array(true, array_column($children, 'current'), true),
                    'children' => $children,
                ];
            } else {
                $items[] = $this->link($item, $language, $currentKey) + ['active' => $item['key'] === $currentKey, 'children' => []];
            }
        }

        return [
            'version' => self::VERSION,
            'language' => $language,
            'homeUrl' => $this->url('main', '', $language),
            'labels' => array_map(fn (array $label): string => $this->text($label, $language), $this->menu['labels']),
            'items' => $items,
            'languages' => $languageLinks,
        ];
    }

    public function url(string $site, string $path, string $language): string
    {
        $relative = '/' . $language . $path;

        return $site === $this->currentSite ? $relative : rtrim($this->siteUrls[$site], '/') . $relative;
    }

    /** An absolute https address without path, query, fragment or credentials; http://localhost only when allowed. */
    public static function isSafeBaseUrl(string $url, bool $allowLocalHttp = false): bool
    {
        $parts = parse_url($url);
        if ($parts === false || !isset($parts['scheme'], $parts['host'])
            || isset($parts['query']) || isset($parts['fragment']) || isset($parts['user']) || isset($parts['pass'])
            || !in_array($parts['path'] ?? '', ['', '/'], true)) {
            return false;
        }

        return $parts['scheme'] === 'https' || ($allowLocalHttp && $parts['scheme'] === 'http' && $parts['host'] === 'localhost');
    }

    /** @param array<string, mixed> $item @return array<string, mixed> */
    private function link(array $item, string $language, ?string $currentKey): array
    {
        return [
            'key' => $item['key'],
            'label' => $this->text($item['label'], $language),
            'description' => isset($item['description']) ? $this->text($item['description'], $language) : null,
            'url' => $this->url($item['site'], $item['path'], $language),
            'current' => $item['key'] === $currentKey,
            'external' => $item['site'] !== $this->currentSite,
        ];
    }

    /** @param array<string, string> $texts */
    private function text(array $texts, string $language): string
    {
        return $texts[$language] ?? $texts[self::FALLBACK_LANGUAGE];
    }
}
