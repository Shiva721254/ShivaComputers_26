<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    public const DEFAULT_LANGUAGE = 'nl';

    /**
     * Maps a URL path to a result. The pattern is a whitelist, so unknown
     * languages and pages can never reach a template.
     *
     * @return array{type: string, language: string, page?: string, location?: string}
     */
    public function match(string $path): array
    {
        if ($path === '/') {
            return [
                'type' => 'redirect',
                'language' => self::DEFAULT_LANGUAGE,
                'status' => 302,
                'location' => '/' . self::DEFAULT_LANGUAGE,
            ];
        }

        $pattern = '#^/(nl|en)(?:/(services|portfolio|about|contact|privacy))?(/?)$#D';
        if (preg_match($pattern, $path, $matches) === 1) {
            $language = $matches[1];
            $page = $matches[2] ?? '';

            if (($matches[3] ?? '') === '/') {
                return [
                    'type' => 'redirect',
                    'language' => $language,
                    'status' => 301,
                    'location' => '/' . $language . ($page === '' ? '' : '/' . $page),
                ];
            }

            return ['type' => 'page', 'language' => $language, 'page' => $page === '' ? 'home' : $page];
        }

        $language = preg_match('#^/en(/|$)#D', $path) === 1 ? 'en' : self::DEFAULT_LANGUAGE;

        return ['type' => 'not_found', 'language' => $language];
    }
}
