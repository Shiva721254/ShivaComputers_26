<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * What Shiva Computers offers, in the order shown in the main menu and on the home page.
 *
 * Two products are pages on this website; the other two are separate sites on their own subdomain.
 * One list drives the menu, the home page cards and the tests, so they can never disagree.
 */
enum Product: string
{
    case Software = 'software';
    case Rescue = 'rescue';
    case Assist = 'assist';
    case RemoteSupport = 'remote';

    /** @return list<self> */
    public static function ordered(): array
    {
        return [self::Software, self::Rescue, self::Assist, self::RemoteSupport];
    }

    /** The page on this website, or null when the product lives on its own subdomain. */
    public function page(): ?Page
    {
        return match ($this) {
            self::Software => Page::Services,
            self::RemoteSupport => Page::RemoteSupport,
            self::Rescue, self::Assist => null,
        };
    }

    public function isExternal(): bool
    {
        return $this->page() === null;
    }
}
