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
    case NotFound = 'not-found';

    /** @return list<self> */
    public static function navigation(): array
    {
        return [self::Home, self::Services, self::Portfolio, self::About, self::Contact];
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
            self::NotFound => 'Errors/not-found',
        };
    }

    public function isIndexable(): bool
    {
        return $this !== self::NotFound;
    }
}
