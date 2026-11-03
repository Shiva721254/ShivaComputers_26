<?php

declare(strict_types=1);

return [
    'name' => 'Shiva Computers',
    // Canonical and hreflang URLs use this value, never the request's Host header.
    'base_url' => rtrim(getenv('APP_URL') ?: 'https://shivacomputers.nl', '/'),
    // 'development' shows errors and screenshot placeholders. Production must keep the default.
    'env' => getenv('APP_ENV') ?: 'production',
    // Keep false until all content is approved: pages then show a notice and are not indexed.
    'content_approved' => false,
    // A null value appears as a visible placeholder. Fill these in before go-live.
    'contact' => [
        'email' => 'Shiva.almere@gmail.com',
        'phone' => '+31 6 28405019',
        'address' => 'Fagotstraat 74, 1312 LC Almere',
        'kvk' => '74760378',
        'vat' => 'NL002498444B35',
    ],
];
