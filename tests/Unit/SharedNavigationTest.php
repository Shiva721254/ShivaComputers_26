<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ShivaNav\Navigation;

/** The shared menu kit in shiva-nav/ (identical in all three sites). */
final class SharedNavigationTest extends TestCase
{
    private const SITES = ['main' => 'https://shivacomputers.nl', 'rescue' => 'https://rescue.shivacomputers.nl', 'assist' => 'https://assist.shivacomputers.nl'];

    public static function setUpBeforeClass(): void
    {
        require_once __DIR__ . '/../../shiva-nav/Navigation.php';
    }

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../shiva-nav/Navigation.php';
    }

    /** @return array<string, array<string, mixed>> key => link, for every link in the menu */
    private static function links(array $built): array
    {
        $links = [];
        foreach ($built['items'] as $item) {
            foreach ($item['children'] === [] ? [$item] : $item['children'] as $link) {
                $links[$link['key']] = $link;
            }
        }

        return $links;
    }

    public function testLinksToTheOwnSiteStayRelativeAndOthersBecomeAbsolute(): void
    {
        $links = self::links((new Navigation(self::SITES, 'assist'))->build('nl', 'assist', []));

        self::assertSame('/nl', $links['assist']['url']);
        self::assertSame('https://shivacomputers.nl/nl/services', $links['services']['url']);
        self::assertSame('https://rescue.shivacomputers.nl/nl', $links['rescue']['url']);
        self::assertFalse($links['assist']['external']);
        self::assertTrue($links['services']['external']);
    }

    public function testEveryLinkHasTextInEnglishAndDutch(): void
    {
        $menu = require __DIR__ . '/../../shiva-nav/menu.php';
        $check = function (array $texts): void {
            self::assertArrayHasKey('en', $texts);
            self::assertArrayHasKey('nl', $texts);
        };
        foreach ($menu['labels'] as $label) {
            $check($label);
        }
        foreach ($menu['items'] as $item) {
            $check($item['label']);
            foreach ($item['children'] ?? [] as $child) {
                $check($child['label']);
                $check($child['description']);
                self::assertTrue(in_array($child['site'], Navigation::SITES, true));
            }
        }
    }

    public function testCurrentPageMarksItsGroup(): void
    {
        $built = (new Navigation(self::SITES, 'main'))->build('en', 'remote', []);
        $groups = array_column($built['items'], 'active', 'key');

        self::assertTrue($groups['help']);
        self::assertFalse($groups['software']);
        self::assertTrue(self::links($built)['remote']['current']);
    }

    public function testUnknownLanguageFallsBackToEnglish(): void
    {
        self::assertSame('Computer help', (new Navigation(self::SITES, 'main'))->build('de', null, [])['items'][2]['label']);
    }

    public static function unsafe(): array
    {
        return [
            'http' => ['http://shivacomputers.nl'],
            'path' => ['https://shivacomputers.nl/x'],
            'query' => ['https://shivacomputers.nl?a=1'],
            'credentials' => ['https://u:p@shivacomputers.nl'],
            'javascript' => ['javascript:alert(1)'],
            'empty' => [''],
        ];
    }

    #[DataProvider('unsafe')]
    public function testUnsafeSiteAddressesAreRefused(string $url): void
    {
        $refused = false;
        try {
            new Navigation(['main' => $url] + self::SITES, 'main');
        } catch (\InvalidArgumentException) {
            $refused = true;
        }
        self::assertTrue($refused);
    }

    public function testLocalDockerAddressesOnlyInDevelopment(): void
    {
        self::assertFalse(Navigation::isSafeBaseUrl('http://localhost:8083'));
        self::assertTrue(Navigation::isSafeBaseUrl('http://localhost:8083', true));
    }

    public function testHeaderTemplateNeverOverwritesThePagesVariables(): void
    {
        $current = 'page value';
        $h = 'page value';
        $languages = 'page value';
        $shivaNav = (new Navigation(self::SITES, 'main'))->build('en', 'home', []);

        ob_start();
        require __DIR__ . '/../../shiva-nav/header.php';
        $html = (string) ob_get_clean();

        self::assertSame('page value', $current);
        self::assertSame('page value', $h);
        self::assertSame('page value', $languages);
        self::assertStringContainsString('data-snav-version="' . Navigation::VERSION . '"', $html);
    }
}
