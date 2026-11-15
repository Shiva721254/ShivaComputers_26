<?php

declare(strict_types=1);

/*
 * The Shiva Computers main menu, shared by shivacomputers.nl, rescue.shivacomputers.nl and assist.shivacomputers.nl.
 *
 * SOURCE OF TRUTH: ShivaComputers_26/shiva-nav/. The other two sites hold an identical copy of this folder.
 * Change it there, copy the folder to the other sites, and run each site's tests (see README.md in this folder).
 *
 * Each link names the site it lives on ('main', 'rescue', 'assist') and its path after /{language}.
 * The site addresses come from each site's own configuration, so local Docker ports work too.
 */

return [
    'labels' => [
        'navigation' => ['en' => 'Main menu', 'nl' => 'Hoofdmenu'],
        'menu' => ['en' => 'Menu', 'nl' => 'Menu'],
        'home' => ['en' => 'Shiva Computers home page', 'nl' => 'Startpagina Shiva Computers'],
        'languages' => ['en' => 'Language', 'nl' => 'Taal'],
    ],
    'items' => [
        [
            'key' => 'home',
            'site' => 'main',
            'path' => '',
            'label' => ['en' => 'Home', 'nl' => 'Home'],
        ],
        [
            'key' => 'software',
            'label' => ['en' => 'Software & websites', 'nl' => 'Software & websites'],
            'children' => [
                [
                    'key' => 'services',
                    'site' => 'main',
                    'path' => '/services',
                    'label' => ['en' => 'Services', 'nl' => 'Diensten'],
                    'description' => ['en' => 'Business applications, web applications and websites', 'nl' => 'Bedrijfsapplicaties, webapplicaties en websites'],
                ],
                [
                    'key' => 'portfolio',
                    'site' => 'main',
                    'path' => '/portfolio',
                    'label' => ['en' => 'Portfolio', 'nl' => 'Portfolio'],
                    'description' => ['en' => 'Projects I have built, with the source code', 'nl' => 'Projecten die ik heb gebouwd, met de broncode'],
                ],
            ],
        ],
        [
            'key' => 'help',
            'label' => ['en' => 'Computer help', 'nl' => 'Computerhulp'],
            'children' => [
                [
                    'key' => 'rescue',
                    'site' => 'rescue',
                    'path' => '',
                    'label' => ['en' => 'Shiva Rescue OS', 'nl' => 'Shiva Rescue OS'],
                    'description' => ['en' => 'Recovery for PCs that no longer start', 'nl' => 'Herstel voor pc’s die niet meer opstarten'],
                ],
                [
                    'key' => 'assist',
                    'site' => 'assist',
                    'path' => '',
                    'label' => ['en' => 'Shiva Assist', 'nl' => 'Shiva Assist'],
                    'description' => ['en' => 'Free Windows scan with clear advice', 'nl' => 'Gratis Windows-scan met helder advies'],
                ],
                [
                    'key' => 'remote',
                    'site' => 'main',
                    'path' => '/remote-support',
                    'label' => ['en' => 'Remote help', 'nl' => 'Hulp op afstand'],
                    'description' => ['en' => 'Help with your computer without a visit', 'nl' => 'Hulp bij uw computer zonder bezoek'],
                ],
            ],
        ],
        [
            'key' => 'about',
            'site' => 'main',
            'path' => '/about',
            'label' => ['en' => 'About', 'nl' => 'Over'],
        ],
        [
            'key' => 'contact',
            'site' => 'main',
            'path' => '/contact',
            'label' => ['en' => 'Contact', 'nl' => 'Contact'],
        ],
    ],
];
