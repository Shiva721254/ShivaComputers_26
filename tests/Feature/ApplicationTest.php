<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Framework\Application;
use App\Framework\Request;
use App\Framework\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ApplicationTest extends TestCase
{
    private function get(string $path, string $method = 'GET'): Response
    {
        /** @var Application $application */
        $application = require __DIR__ . '/../../config/bootstrap.php';

        return $application->handle(new Request($method, $path));
    }

    public static function pages(): array
    {
        $paths = [];
        foreach (['nl', 'en'] as $language) {
            foreach (['', '/services', '/portfolio', '/about', '/contact', '/privacy'] as $page) {
                $paths[$language . $page] = ['/' . $language . $page, $language];
            }
        }

        return $paths;
    }

    #[DataProvider('pages')]
    public function testPageLoadsInTheCorrectLanguage(string $path, string $language): void
    {
        $response = $this->get($path);

        self::assertSame(200, $response->status);
        self::assertStringContainsString('<html lang="' . $language . '">', $response->body);
      $baseUrl = (require __DIR__ . '/../../config/app.php')['base_url'];
self::assertStringContainsString('rel="canonical" href="' . $baseUrl . $path . '"', $response->body);
        self::assertArrayHasKey('Content-Security-Policy', $response->headers);
    }

    public function testRootRedirectsToDutch(): void
    {
        $response = $this->get('/');

        self::assertSame(302, $response->status);
        self::assertSame('/nl', $response->headers['Location']);
    }

    public function testTrailingSlashRedirectsToCleanUrl(): void
    {
        $response = $this->get('/nl/services/');

        self::assertSame(301, $response->status);
        self::assertSame('/nl/services', $response->headers['Location']);
    }

    public static function invalidPaths(): array
    {
        return [['/de'], ['/de/'], ['/nl/nope'], ['/nl/services/x'], ['/NL'], ['/\\evil.example/']];
    }

    #[DataProvider('invalidPaths')]
    public function testInvalidPathReturnsNotFoundWithoutRedirect(string $path): void
    {
        $response = $this->get($path);

        self::assertSame(404, $response->status);
        self::assertArrayNotHasKey('Location', $response->headers);
        self::assertStringContainsString('noindex', $response->body);
    }

    public function testNotFoundUsesTheLanguageFromThePath(): void
    {
        self::assertStringContainsString('Page not found', $this->get('/en/nope')->body);
    }

    public function testPostIsNotAllowed(): void
    {
        $response = $this->get('/nl', 'POST');

        self::assertSame(405, $response->status);
        self::assertStringContainsString('GET', $response->headers['Allow']);
    }
}
