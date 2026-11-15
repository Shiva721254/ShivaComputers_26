<?php

declare(strict_types=1);

return [
    'name' => 'Shiva Computers',
    // Canonical and hreflang URLs use this value, never the request's Host header.
    'base_url' => rtrim(getenv('APP_URL') ?: 'https://shivacomputers.nl', '/'),
    // 'development' shows errors and screenshot placeholders. Production must keep the default.
    'env' => getenv('APP_ENV') ?: 'production',
    // Keep false until all content is approved: pages then show a notice and are not indexed.
    'content_approved' => true,
    // A null value appears as a visible placeholder. Fill these in before go-live.
    // Remote support portal (separate app on the VPS). Leave null until the portal passes its
    // production gate: the page then asks visitors to call, and /hulp and /help open that page.
    // Example: 'https://remote.shivacomputers.nl'. The REMOTE_SUPPORT_URL environment variable overrides it.
    'remote_support_url' => getenv('REMOTE_SUPPORT_URL') ?: null,
    // Product sites on their own subdomain, linked from the main menu and the home page.
    // Only https addresses without a path are accepted. Override locally with the environment variables.
    'product_sites' => [
        'rescue' => getenv('RESCUE_SITE_URL') ?: 'https://rescue.shivacomputers.nl',
        'assist' => getenv('ASSIST_SITE_URL') ?: 'https://assist.shivacomputers.nl',
    ],
    'contact' => [
        'email' => 'Shiva.almere@gmail.com',
        'phone' => '+31 6 28405019',
        'address' => 'Fagotstraat 74, 1312 LC Almere',
        'kvk' => '74760378',
        'vat' => 'NL002498444B35',
    ],
];
