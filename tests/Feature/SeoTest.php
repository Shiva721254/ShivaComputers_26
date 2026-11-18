<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Framework\Application;
use App\Framework\Request;
use App\Framework\Response;
use PHPUnit\Framework\TestCase;

final class SeoTest extends TestCase
{
    private function get(string $path): Response
    {
        /** @var Application $application */
        $application = require __DIR__ . '/../../config/bootstrap.php';

        return $application->handle(new Request('GET', $path));
    }

    private function baseUrl(): string
    {
        return (require __DIR__ . '/../../config/app.php')['base_url'];
    }

    public function testRobotsTxtPointsToTheSitemap(): void
    {
        $response = $this->get('/robots.txt');

        self::assertSame(200, $response->status);
        self::assertStringStartsWith('text/plain', $response->headers['Content-Type']);
        self::assertStringContainsString('User-agent: *', $response->body);
        self::assertStringContainsString('Sitemap: ' . $this->baseUrl() . '/sitemap.xml', $response->body);
    }

    public function testSitemapListsEveryPageInBothLanguages(): void
    {
        $response = $this->get('/sitemap.xml');

        self::assertSame(200, $response->status);
        self::assertStringStartsWith('application/xml', $response->headers['Content-Type']);

        $xml = simplexml_load_string($response->body);
        self::assertNotFalse($xml);
        $locations = [];
        foreach ($xml->url as $url) {
            $locations[] = (string) $url->loc;
        }

        self::assertCount(14, $locations);
        self::assertContains($this->baseUrl() . '/en/portfolio', $locations);
        self::assertContains($this->baseUrl() . '/nl/remote-support', $locations);
    }

    public function testHomePageDescribesTheBusinessForSearchEngines(): void
    {
        $body = $this->get('/en')->body;

        self::assertSame(1, preg_match('#<script type="application/ld\+json">(.+?)</script>#s', $body, $match));
        $data = json_decode($match[1], true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('ProfessionalService', $data['@type']);
        self::assertSame('Shiva Computers', $data['name']);
        self::assertSame('NL', $data['address']['addressCountry']);
        self::assertStringContainsString('property="og:title"', $body);
    }

    public function testOtherPagesHaveNoBusinessData(): void
    {
        self::assertStringNotContainsString('application/ld+json', $this->get('/en/services')->body);
    }
}
