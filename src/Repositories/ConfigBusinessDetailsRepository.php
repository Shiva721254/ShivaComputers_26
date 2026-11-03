<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\BusinessDetails;
use App\Repositories\Interfaces\IBusinessDetailsRepository;

final class ConfigBusinessDetailsRepository implements IBusinessDetailsRepository
{
    /** @param array<string, string|null> $contact */
    public function __construct(
        private readonly string $name,
        private readonly array $contact,
    ) {
    }

    public function get(): BusinessDetails
    {
        return new BusinessDetails(
            $this->name,
            $this->contact['email'] ?? null,
            $this->contact['phone'] ?? null,
            $this->contact['address'] ?? null,
            $this->contact['kvk'] ?? null,
            $this->contact['vat'] ?? null,
        );
    }
}
