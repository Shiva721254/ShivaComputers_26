<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Controllers\RemoteSupportController;
use App\Framework\Application;
use App\Framework\Container;
use App\Framework\Request;
use App\Framework\Response;
use App\Framework\Router;
use App\Middleware\SecurityHeadersMiddleware;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function FastRoute\simpleDispatcher;

final class RemoteSupportTest extends TestCase
{
    private const ROOT = __DIR__ . '/../..';

    /** Builds the real application with a chosen portal URL, the same way config/bootstrap.php does. */
    private function application(?string $portalUrl, string $env = 'production'): Application
    {
        $config = ['remote_support_url' => $portalUrl, 'env' => $env] + (require self::ROOT . '/config/app.php');
        $container = new Container();
        (require self::ROOT . '/config/services.php')($container, $config, realpath(self::ROOT));

        return new Application(
            new Router(simpleDispatcher(require self::ROOT . '/config/routes.php')),
            $container,
            [new SecurityHeadersMiddleware()],
        );
    }

    private function get(Application $application, string $path): Response
    {
        return $application->handle(new Request('GET', $path));
    }

    public function testShortLinksOpenTheInformationPageWhileThePortalIsOff(): void
    {
        $application = $this->application(null);

        self::assertSame('/nl/remote-support', $this->get($application, '/hulp')->headers['Location']);
        self::assertSame('/en/remote-support', $this->get($application, '/help')->headers['Location']);
        self::assertSame(302, $this->get($application, '/hulp')->status);
    }

    public function testShortLinksOpenThePortalInTheRightLanguageWhenItIsLive(): void
    {
        $application = $this->application('https://remote.shivacomputers.nl/');

        self::assertSame('https://remote.shivacomputers.nl/nl', $this->get($application, '/hulp')->headers['Location']);
        self::assertSame('https://remote.shivacomputers.nl/en', $this->get($application, '/help')->headers['Location']);
        self::assertSame('no-store', $this->get($application, '/hulp')->headers['Cache-Control']);
    }

    public function testPageAsksVisitorsToCallWhileThePortalIsOff(): void
    {
        $body = $this->get($this->application(null), '/nl/remote-support')->body;

        self::assertStringContainsString('href="tel:+31628405019"', $body);
        self::assertStringNotContainsString('remote.shivacomputers.nl', $body);
    }

    public function testPageLinksToThePortalWhenItIsLive(): void
    {
        $body = $this->get($this->application('https://remote.shivacomputers.nl'), '/en/remote-support')->body;

        self::assertStringContainsString('href="https://remote.shivacomputers.nl/en"', $body);
        self::assertStringContainsString('Enter your code', $body);
    }

    public function testPageIsInTheMainNavigationAndInTheFooter(): void
    {
        $body = $this->get($this->application(null), '/nl')->body;
        $navigation = substr($body, strpos($body, '<nav'), strpos($body, '</nav>') - strpos($body, '<nav'));

        self::assertStringContainsString('href="/nl/remote-support">Hulp op afstand</a>', $navigation);
        self::assertStringContainsString('href="/nl/remote-support"', substr($body, strpos($body, '<footer')));
    }

    public function testServicesPageLinksToRemoteSupport(): void
    {
        self::assertStringContainsString('href="/en/remote-support"', $this->get($this->application(null), '/en/services')->body);
    }

    public static function unsafePortalUrls(): array
    {
        return [
            'plain http' => ['http://remote.shivacomputers.nl'],
            'with path' => ['https://remote.shivacomputers.nl/evil'],
            'with query' => ['https://remote.shivacomputers.nl?next=//evil.example'],
            'with credentials' => ['https://user:pass@remote.shivacomputers.nl'],
            'protocol relative' => ['//evil.example'],
            'javascript' => ['javascript:alert(1)'],
            'not a url' => ['remote support'],
        ];
    }

    #[DataProvider('unsafePortalUrls')]
    public function testUnsafePortalUrlsAreSwitchedOff(string $url): void
    {
        self::assertNull(RemoteSupportController::validPortalUrl($url));
    }

    public function testLocalHttpIsOnlyAllowedInDevelopment(): void
    {
        self::assertSame('http://localhost:8081', RemoteSupportController::validPortalUrl('http://localhost:8081', true));
        self::assertNull(RemoteSupportController::validPortalUrl('http://localhost:8081', false));
    }
}
