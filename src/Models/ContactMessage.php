<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Language;

final readonly class ContactMessage
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $message,
        public Language $language,
    ) {
    }
}
