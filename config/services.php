<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\ContactController;
use App\Controllers\ErrorController;
use App\Controllers\HomeController;
use App\Controllers\PortfolioController;
use App\Controllers\PrivacyController;
use App\Controllers\RemoteSupportController;
use App\Controllers\SeoController;
use App\Framework\FormToken;
use App\Framework\Storage;
use App\Services\ContactFormService;
use App\Services\FileMailer;
use App\Services\FileRateLimiter;
use App\Services\Interfaces\IContactFormService;
use App\Services\Interfaces\IMailer;
use App\Services\Interfaces\IRateLimiter;
use App\Services\MailFormatter;
use App\Services\NativeMailer;
use App\Controllers\ServiceController;
use App\Framework\Container;
use App\Framework\Translator;
use App\Framework\View;
use App\Repositories\ConfigBusinessDetailsRepository;
use App\Repositories\FileOfferingRepository;
use App\Repositories\FileProjectRepository;
use App\Repositories\Interfaces\IBusinessDetailsRepository;
use App\Repositories\Interfaces\IOfferingRepository;
use App\Repositories\Interfaces\IProjectRepository;
use App\Services\BusinessDetailsService;
use App\Services\Interfaces\IBusinessDetailsService;
use App\Services\Interfaces\IOfferingService;
use App\Services\Interfaces\IProjectService;
use App\Services\OfferingService;
use App\Services\ProjectService;
use App\ViewModels\PageViewModelFactory;
use App\ViewModels\ProductLinks;

/**
 * The one place that decides which class implements each interface.
 * Moving projects to MySQL later means changing one line here.
 */
return static function (Container $c, array $config, string $root): void {
    // The shared Shiva Computers menu (identical copy in every site, see shiva-nav/README.md).
    require_once $root . '/shiva-nav/Navigation.php';

    // Repositories
    $c->set(IProjectRepository::class, fn () => new FileProjectRepository($root . '/data/projects.php'));
    $c->set(IOfferingRepository::class, fn () => new FileOfferingRepository($root . '/data/offerings.php'));
    $c->set(IBusinessDetailsRepository::class, fn () => new ConfigBusinessDetailsRepository($config['name'], $config['contact']));

    // Services
    $c->set(IProjectService::class, fn (Container $c) => new ProjectService($c->get(IProjectRepository::class)));
    $c->set(IOfferingService::class, fn (Container $c) => new OfferingService($c->get(IOfferingRepository::class)));
    $c->set(IBusinessDetailsService::class, fn (Container $c) => new BusinessDetailsService($c->get(IBusinessDetailsRepository::class)));

    // Contact form
    $c->set(Storage::class, fn () => new Storage($config['storage']));
    $c->set(FormToken::class, fn (Container $c) => new FormToken($c->get(Storage::class)->secret()));
    $c->set(IRateLimiter::class, fn (Container $c) => new FileRateLimiter($c->get(Storage::class)->path('rate-limit.json'), $c->get(Storage::class)->secret()));
    $c->set(MailFormatter::class, fn () => new MailFormatter($config['mail']['from'], $config['mail']['from_name']));
    $c->set(FileMailer::class, fn (Container $c) => new FileMailer($c->get(Storage::class)->path('unsent'), $c->get(MailFormatter::class)));
    $c->set(IMailer::class, fn (Container $c) => $config['mail']['transport'] === 'mail'
        ? new NativeMailer($config['mail']['to'], $c->get(MailFormatter::class), $c->get(FileMailer::class))
        : $c->get(FileMailer::class));
    $c->set(IContactFormService::class, fn (Container $c) => new ContactFormService(
        $c->get(FormToken::class),
        $c->get(IRateLimiter::class),
        $c->get(IMailer::class),
    ));

    // Presentation
    $c->set(Translator::class, fn () => new Translator($root . '/translations'));
    $c->set(View::class, fn () => new View($root . '/src/Views'));
    $c->set(ProductLinks::class, fn (Container $c) => new ProductLinks(
        $c->get(Translator::class),
        $config['product_sites'],
        $config['env'] === 'development',
    ));
    $c->set(ShivaNav\Navigation::class, fn () => new ShivaNav\Navigation(
        ['main' => $config['base_url'], 'rescue' => $config['product_sites']['rescue'], 'assist' => $config['product_sites']['assist']],
        'main',
        $config['env'] === 'development',
    ));
    $c->set(PageViewModelFactory::class, fn (Container $c) => new PageViewModelFactory(
        $c->get(Translator::class),
        $c->get(IBusinessDetailsService::class),
        $config['base_url'],
        $config['content_approved'],
        $config['env'] === 'development',
        $c->get(ShivaNav\Navigation::class),
    ));

    // Controllers
    $presentation = fn (Container $c): array => [$c->get(View::class), $c->get(PageViewModelFactory::class), $c->get(Translator::class)];

    $c->set(HomeController::class, fn (Container $c) => new HomeController(...[...$presentation($c), $c->get(IOfferingService::class), $c->get(IProjectService::class), $c->get(ProductLinks::class)]));
    $c->set(ServiceController::class, fn (Container $c) => new ServiceController(...[...$presentation($c), $c->get(IOfferingService::class)]));
    $c->set(PortfolioController::class, fn (Container $c) => new PortfolioController(...[...$presentation($c), $c->get(IProjectService::class)]));
    $c->set(AboutController::class, fn (Container $c) => new AboutController(...$presentation($c)));
    $c->set(ContactController::class, fn (Container $c) => new ContactController(...[...$presentation($c), $c->get(IContactFormService::class)]));
    $c->set(PrivacyController::class, fn (Container $c) => new PrivacyController(...$presentation($c)));
    $c->set(RemoteSupportController::class, fn (Container $c) => new RemoteSupportController(
        ...[...$presentation($c), RemoteSupportController::validPortalUrl($config['remote_support_url'] ?? null, $config['env'] === 'development')],
    ));
    $c->set(SeoController::class, fn () => new SeoController($config['base_url'], $config['content_approved']));
    $c->set(ErrorController::class, fn (Container $c) => new ErrorController(...$presentation($c)));
};
