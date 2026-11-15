<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Enums\Product;
use App\Framework\Application;
use App\Framework\Container;
use App\Framework\Request;
use App\Framework\Response;
use App\Framework\Router;
use App\Middleware\SecurityHeadersMiddleware;
use App\ViewModels\ProductLinks;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function FastRoute\simpleDispatcher;

/** The four products in the main menu and on the home page, in a fixed order. */
final class ProductsTest extends TestCase
{
    private const ROOT = __DIR__ . '/../..';

    /** @param array<string, mixed> $overrides */
    private function get(string $path, array $overrides = []): Response
    {
        $config = $overrides + (require self::ROOT . '/config/app.php');
        $container = new Container();
        (require self::ROOT . '/config/services.php')($container, $config, realpath(self::ROOT));
        $application = new Application(
            new Router(simpleDispatcher(require self::ROOT . '/config/routes.php')),
            $container,
            [new SecurityHeadersMiddleware()],
        );

        return $application->handle(new Request('GET', $path));
    }

    /** The desktop menu: everything between the first <nav and its closing tag. */
    private static function navigation(string $body): string
    {
        $start = (int) strpos($body, '<nav');

        return substr($body, $start, (int) strpos($body, '</nav>', $start) - $start);
    }

    /** @param list<string> $needles */
    private static function assertInOrder(array $needles, string $haystack): void
    {
        $last = -1;
        foreach ($needles as $needle) {
            $position = strpos($haystack, $needle);
            self::assertTrue($position !== false, 'Missing: ' . $needle);
            self::assertTrue($position > $last, 'Out of order: ' . $needle);
            $last = (int) $position;
        }
    }

    public static function languages(): array
    {
        return [
            'English' => ['en', 'Remote help', 'What Shiva Computers offers'],
            'Dutch' => ['nl', 'Hulp op afstand', 'Wat Shiva Computers biedt'],
        ];
    }

    #[DataProvider('languages')]
    public function testMenuShowsTheProductsFirstInTheAgreedOrder(string $language, string $remoteLabel): void
    {
        $menu = self::navigation($this->get('/' . $language)->body);

        self::assertInOrder([
            'href="/' . $language . '/services">Software &amp; websites</a>',
            'href="https://rescue.shivacomputers.nl/' . $language . '" class="is-external">Rescue OS</a>',
            'href="https://assist.shivacomputers.nl/' . $language . '" class="is-external">Shiva Assist</a>',
            'href="/' . $language . '/remote-support">' . $remoteLabel . '</a>',
            'href="/' . $language . '/portfolio"',
            'href="/' . $language . '/about"',
            'href="/' . $language . '/contact"',
        ], $menu);
    }

    #[DataProvider('languages')]
    public function testHomePageShowsFourProductCardsInOrder(string $language, string $remoteLabel, string $heading): void
    {
        $body = $this->get('/' . $language)->body;
        $section = substr($body, (int) strpos($body, 'class="section products"'));

        self::assertStringContainsString($heading, $section);
        self::assertSame(4, substr_count($section, 'class="product-card '));
        self::assertInOrder([
            'class="product-card product-card--software" href="/' . $language . '/services"',
            'class="product-card product-card--rescue" href="https://rescue.shivacomputers.nl/' . $language . '"',
            'class="product-card product-card--assist" href="https://assist.shivacomputers.nl/' . $language . '"',
            'class="product-card product-card--remote" href="/' . $language . '/remote-support"',
        ], $section);
    }

    public function testCardsAppearBeforeTheServiceDetails(): void
    {
        $body = $this->get('/en')->body;

        self::assertInOrder(['class="hero"', 'class="section products"', 'What I can build for you'], $body);
    }

    public function testDevelopmentStatusIsShownHonestly(): void
    {
        $body = $this->get('/en')->body;

        self::assertSame(2, substr_count($body, '<span class="product-tag">In development</span>'));
    }

    public static function currentPages(): array
    {
        return [
            'services' => ['/en/services', 'href="/en/services" aria-current="page">Software &amp; websites</a>'],
            'remote support' => ['/nl/remote-support', 'href="/nl/remote-support" aria-current="page">Hulp op afstand</a>'],
            'portfolio' => ['/en/portfolio', 'href="/en/portfolio" aria-current="page">'],
        ];
    }

    #[DataProvider('currentPages')]
    public function testMenuMarksTheCurrentPage(string $path, string $expected): void
    {
        self::assertStringContainsString($expected, self::navigation($this->get($path)->body));
    }

    public function testHomeIsReachedThroughTheLogoNotTheMenu(): void
    {
        $body = $this->get('/en')->body;

        self::assertStringContainsString('<a class="brand" href="/en">', $body);
        self::assertStringNotContainsString('>Home</a>', self::navigation($body));
    }

    public function testPhoneMenuContainsTheSameLinksAndWorksWithoutJavaScript(): void
    {
        $body = $this->get('/nl/about')->body;
        $phone = substr($body, (int) strpos($body, '<details class="nav-menu">'));
        $phone = substr($phone, 0, (int) strpos($phone, '</details>'));

        self::assertStringContainsString('<summary>Menu</summary>', $phone);
        self::assertSame(substr_count(self::navigation($body), '<li>'), substr_count($phone, '<li>'));
        self::assertStringNotContainsString('<script', $body);
    }

    public function testProductSiteAddressesCanBeConfigured(): void
    {
        $body = $this->get('/en', ['product_sites' => ['rescue' => 'https://r.example.nl', 'assist' => 'https://a.example.nl/']]);

        self::assertStringContainsString('href="https://r.example.nl/en"', $body->body);
        self::assertStringContainsString('href="https://a.example.nl/en"', $body->body);
    }

    public static function unsafeSiteUrls(): array
    {
        return [
            'plain http' => ['http://rescue.shivacomputers.nl'],
            'with a path' => ['https://rescue.shivacomputers.nl/evil'],
            'with a query' => ['https://rescue.shivacomputers.nl?x=1'],
            'with a fragment' => ['https://rescue.shivacomputers.nl#x'],
            'with credentials' => ['https://user@rescue.shivacomputers.nl'],
            'javascript' => ['javascript:alert(1)'],
            'relative' => ['//evil.example'],
            'empty' => [''],
        ];
    }

    #[DataProvider('unsafeSiteUrls')]
    public function testUnsafeSiteUrlsAreRejected(string $url): void
    {
        self::assertFalse(ProductLinks::isSafeSiteUrl($url));
    }

    public function testLocalHttpIsOnlyAllowedInDevelopment(): void
    {
        self::assertFalse(ProductLinks::isSafeSiteUrl('http://localhost:8082'));
        self::assertTrue(ProductLinks::isSafeSiteUrl('http://localhost:8082', true));
        self::assertTrue(ProductLinks::isSafeSiteUrl('https://rescue.shivacomputers.nl'));
        self::assertTrue(ProductLinks::isSafeSiteUrl('https://rescue.shivacomputers.nl/'));
    }

    public function testAnUnsafeSettingStopsTheSiteInsteadOfLinkingAnywhere(): void
    {
        // The site answers with its normal error page; the bad link never reaches a visitor.
        $response = $this->get('/en', ['product_sites' => ['rescue' => 'http://evil.example', 'assist' => 'https://assist.shivacomputers.nl']]);

        self::assertSame(500, $response->status);
        self::assertStringNotContainsString('evil.example', $response->body);
    }

    public function testEveryProductHasTextInBothLanguages(): void
    {
        foreach (['nl', 'en'] as $language) {
            $texts = require self::ROOT . '/translations/' . $language . '.php';
            foreach (Product::ordered() as $product) {
                foreach (['tag', 'title', 'text', 'link'] as $field) {
                    self::assertArrayHasKey('products.' . $product->value . '.' . $field, $texts);
                }
                self::assertArrayHasKey('nav.product.' . $product->value, $texts);
            }
        }
    }
}
