<?php

declare(strict_types=1);

namespace App\Models;

final readonly class BusinessDetails
{
    public function __construct(
        public string $name,
        public ?string $email,
        public ?string $phone,
        public ?string $address,
        public ?string $kvk,
        public ?string $vat,
    ) {
    }
}
