<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Language;
use App\Enums\Page;
use App\Framework\Response;
use App\Framework\Translator;
use App\Framework\View;
use App\ViewModels\PageViewModelFactory;

/**
 * Public information page for remote computer help.
 * The session itself runs in the separate support portal; this site only links to it,
 * so it keeps working without cookies, forms or a database.
 */
final class RemoteSupportController extends Controller
{
    public function __construct(
        View $view,
        PageViewModelFactory $pages,
        Translator $translator,
        private readonly ?string $portalUrl,
    ) {
        parent::__construct($view, $pages, $translator);
    }

    // GET: /{language}/remote-support
    public function index(Language $language): Response
    {
        return $this->render($language, Page::RemoteSupport, [
            'portalUrl' => $this->portalUrl === null ? null : $this->portalUrl . '/' . $language->value,
        ]);
    }

    // GET: /hulp
    public function startDutch(): Response
    {
        return $this->start(Language::Dutch);
    }

    // GET: /help
    public function startEnglish(): Response
    {
        return $this->start(Language::English);
    }

    /**
     * Sends the visitor to the portal when it is live, otherwise to the information page.
     * The target comes from configuration only, never from the request, so it cannot be abused as an open redirect.
     */
    private function start(Language $language): Response
    {
        $target = $this->portalUrl === null
            ? Page::RemoteSupport->path($language)
            : $this->portalUrl . '/' . $language->value;

        return Response::redirect($target)->withHeaders(['Cache-Control' => 'no-store']);
    }

    /**
     * Accepts only an absolute https URL without path, query or credentials, such as https://remote.shivacomputers.nl.
     * Plain http is allowed for localhost during development. Anything else switches the portal link off.
     */
    public static function validPortalUrl(?string $url, bool $isDevelopment = false): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $url = rtrim(trim($url), '/');
        $parts = parse_url($url);
        if ($parts === false || !isset($parts['scheme'], $parts['host'])) {
            return null;
        }

        $isLocalHttp = $isDevelopment && $parts['scheme'] === 'http' && in_array($parts['host'], ['localhost', '127.0.0.1'], true);
        $hasOnlyOrigin = !isset($parts['user']) && !isset($parts['pass']) && !isset($parts['query']) && !isset($parts['fragment'])
            && ($parts['path'] ?? '') === '';

        return ($parts['scheme'] === 'https' || $isLocalHttp) && $hasOnlyOrigin ? $url : null;
    }
}
