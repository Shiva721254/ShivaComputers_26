<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Language;
use App\Enums\Page;
use App\Framework\Response;

/** Files that search engines read: robots.txt and sitemap.xml. */
final class SeoController
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly bool $contentApproved,
    ) {
    }

    // GET: /robots.txt
    public function robots(): Response
    {
        // Until the content is approved, ask search engines to stay away entirely.
        $body = $this->contentApproved
            ? "User-agent: *\nAllow: /\n\nSitemap: {$this->baseUrl}/sitemap.xml\n"
            : "User-agent: *\nDisallow: /\n";

        return Response::text($body, 200);
    }

    // GET: /sitemap.xml
    public function sitemap(): Response
    {
        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');

        foreach (Page::cases() as $page) {
            if (!$page->isIndexable()) {
                continue;
            }
            foreach (Language::cases() as $language) {
                $xml->startElement('url');
                $xml->writeElement('loc', $this->baseUrl . $page->path($language));
                foreach ([...Language::cases(), null] as $alternate) {
                    $xml->startElement('xhtml:link');
                    $xml->writeAttribute('rel', 'alternate');
                    $xml->writeAttribute('hreflang', $alternate?->value ?? 'x-default');
                    $xml->writeAttribute('href', $this->baseUrl . $page->path($alternate ?? Language::DEFAULT));
                    $xml->endElement();
                }
                $xml->endElement();
            }
        }

        $xml->endElement();
        $xml->endDocument();

        return new Response($xml->outputMemory(), 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }
}
