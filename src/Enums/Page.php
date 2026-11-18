<?php

declare(strict_types=1);

namespace App\Enums;

enum Page: string
{
    case Home = 'home';
    case Services = 'services';
    case Portfolio = 'portfolio';
    case About = 'about';
    case Contact = 'contact';
    case Privacy = 'privacy';
    case RemoteSupport = 'remote-support';
    case NotFound = 'not-found';

    /** Key of this page in the shared Shiva Computers menu (shiva-nav/menu.php), or null when it is not in the menu. */
    public function navigationKey(): ?string
    {
        return match ($this) {
            self::Home => 'home',
            self::Services => 'services',
            self::Portfolio => 'portfolio',
            self::About => 'about',
            self::Contact => 'contact',
            self::RemoteSupport => 'remote',
            self::Privacy, self::NotFound => null,
        };
    }

    public function path(Language $language): string
    {
        return '/' . $language->value . match ($this) {
            self::Home, self::NotFound => '',
            default => '/' . $this->value,
        };
    }

    public function template(): string
    {
        return match ($this) {
            self::Home => 'Home/index',
            self::Services => 'Services/index',
            self::Portfolio => 'Portfolio/index',
            self::About => 'About/index',
            self::Contact => 'Contact/index',
            self::Privacy => 'Privacy/index',
            self::RemoteSupport => 'RemoteSupport/index',
            self::NotFound => 'Errors/not-found',
        };
    }

    public function isIndexable(): bool
    {
        return $this !== self::NotFound;
    }
}
