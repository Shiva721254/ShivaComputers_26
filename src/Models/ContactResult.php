<?php

declare(strict_types=1);

namespace App\Models;

/** The outcome of a contact form submission. */
final readonly class ContactResult
{
    public const SENT = 'sent';
    public const INVALID = 'invalid';
    public const EXPIRED = 'expired';
    public const TOO_MANY = 'too_many';
    public const FAILED = 'failed';

    /** @param array<string, string> $errors field name => translation key */
    public function __construct(
        public string $status,
        public array $errors = [],
    ) {
    }

    public function isSent(): bool
    {
        return $this->status === self::SENT;
    }
}
