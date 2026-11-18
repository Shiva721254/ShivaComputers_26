<?php

declare(strict_types=1);

// Repository links were confirmed to exist. Add a project only after checking its repository.
return [
    [
        'name' => 'Haarlem Festival',
        'image' => '/assets/img/projects/haarlem-festival.jpg',
        'imageAlt' => [
            'nl' => 'Haarlem Festival in de browser: de homepage met de festivalonderdelen en de checkout met vier stappen en een besteloverzicht.',
            'en' => 'Haarlem Festival in the browser: the homepage with the festival events, and the four-step checkout with an order summary.',
        ],
        'repository' => 'https://github.com/Shiva721254/Haarlem-Festival-Project_2026',
        'stack' => ['PHP', 'MariaDB', 'Stripe', 'Docker'],
        'featured' => true,
        'label' => ['nl' => 'Studieproject, team van 4', 'en' => 'Study project, team of 4'],
        'description' => [
            'nl' => 'Ticketwebsite met reserveringen, Stripe-betaling in testmodus, QR-tickets, PDF-facturen en een CMS.',
            'en' => 'Ticketing website with reservations, Stripe payment in test mode, QR tickets, PDF invoices and a CMS.',
        ],
    ],
    [
        'name' => 'Chapeau',
        'image' => '/assets/img/projects/chapeau.jpg',
        'imageAlt' => [
            'nl' => 'Chapeau op een tablet: links de plattegrond met vrije en bezette tafels, rechts het opnemen van een bestelling per gast en per gang.',
            'en' => 'Chapeau on a tablet: on the left the floor plan with free and occupied tables, on the right taking an order per guest and per course.',
        ],
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
        'image' => '/assets/img/projects/garden-group.jpg',
        'imageAlt' => [
            'nl' => 'Ontwerp van het incidentendashboard van The Garden Group, met aantallen per status, een grafiek per prioriteit en recente incidenten.',
            'en' => 'Design mockup of The Garden Group incident dashboard, with counts per status, a chart per priority and recent incidents.',
        ],
        'imageNote' => [
            'nl' => 'Ontwerp (mockup), niet de huidige versie van de applicatie.',
            'en' => 'Design mockup, not the current version of the application.',
        ],
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
        'image' => '/assets/img/projects/beatrixpark.jpg',
        'imageAlt' => [
            'nl' => 'De homepage van de Beatrixpark-website met een groene menubalk, een foto van het park en informatie voor bezoekers.',
            'en' => 'The Beatrixpark website homepage with a green menu bar, a photo of the park and information for visitors.',
        ],
        'repository' => 'https://github.com/Shiva721254/Web_Markup_2025',
        'stack' => ['HTML5', 'CSS3'],
        'featured' => false,
        'label' => ['nl' => 'Studieproject', 'en' => 'Study project'],
        'description' => [
            'nl' => 'Statische, responsieve informatiewebsite over Koningin Beatrixpark, gemaakt met semantische HTML en CSS.',
            'en' => 'Static, responsive information website about Koningin Beatrixpark, made with semantic HTML and CSS.',
        ],
    ],
           [
           'name' => 'Bid Shop',
        'image' => '/assets/img/projects/bid-shop.jpg',
        'imageAlt' => [
            'nl' => 'Bid Shop in de browser: de lijst met veilingen en een veiling met het huidige bod en een aftelklok.',
            'en' => 'Bid Shop in the browser: the list of auctions, and an auction with the current bid and a countdown timer.',
        ],
           'repository' => 'https://github.com/Shiva721254/Web-Shop-Bidding',
           'stack' => ['Vue 3', 'PHP', 'MariaDB', 'JWT'],
           'featured' => false,
           'label' => ['nl' => 'Individueel studieproject', 'en' => 'Individual study project'],
           'description' => [
               'nl' => 'Webshop waar klanten producten kopen voor een vaste prijs of bieden op veilingen, met inloggen en een aparte REST API.',
               'en' => 'Web shop where customers buy products at a fixed price or bid on auctions, with login and a separate REST API.',
           ],
       ],
];
