<?php

declare(strict_types=1);

// Repository links were confirmed to exist. Add a project only after checking its repository.
return [
    [
        'name' => 'Haarlem Festival',
        'repository' => 'https://github.com/Shiva721254/Haarlem-Festival-Project_2026',
        'stack' => ['PHP', 'MariaDB', 'Stripe', 'Docker'],
        'featured' => true,
        'label' => ['nl' => 'Studieproject', 'en' => 'Study project'],
        'description' => [
            'nl' => 'Ticketwebsite met reserveringen, Stripe-betaling in testmodus, QR-tickets, PDF-facturen en een CMS.',
            'en' => 'Ticketing website with reservations, Stripe payment in test mode, QR tickets, PDF invoices and a CMS.',
        ],
    ],
    [
        'name' => 'Chapeau',
        'repository' => 'https://github.com/Rakib730066/Chapeau-ordering-system',
        'stack' => ['C#', 'ASP.NET Core MVC', 'SQL Server'],
        'featured' => true,
        'label' => ['nl' => 'Studieproject, team van 6', 'en' => 'Study project, team of 6'],
        'description' => [
            'nl' => 'Kassa- en bestelsysteem voor een restaurant, van tafelbeheer en bestellen tot keuken, bar en betaling.',
            'en' => 'Restaurant point-of-sale system, from table management and ordering to kitchen, bar and payment.',
        ],
    ],
    [
        'name' => 'The Garden Group',
        'repository' => 'https://github.com/Shiva721254/The_Garden_group',
        'stack' => ['C#', 'ASP.NET Core', 'MongoDB'],
        'featured' => true,
        'label' => ['nl' => 'Studieproject', 'en' => 'Study project'],
        'description' => [
            'nl' => 'Servicedesk-applicatie voor tickets en medewerkers, met inloggen, zoeken en een dashboard.',
            'en' => 'Service desk application for tickets and employees, with login, search and a dashboard.',
        ],
    ],
    [
        'name' => 'Beatrixpark Almere',
        'repository' => 'https://github.com/Shiva721254/Web_Markup_2025',
        'stack' => ['HTML5', 'CSS3'],
        'featured' => false,
        'label' => ['nl' => 'Studieproject', 'en' => 'Study project'],
        'description' => [
            'nl' => 'Statische, responsieve informatiewebsite over Koningin Beatrixpark, gemaakt met semantische HTML en CSS.',
            'en' => 'Static, responsive information website about Koningin Beatrixpark, made with semantic HTML and CSS.',
        ],
    ],
];
