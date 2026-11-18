<?php

declare(strict_types=1);

namespace App\Framework;

/**
 * A signed timestamp in a hidden form field. It needs no cookie or session, and lets the
 * server check that a form was opened on this site and not filled in impossibly fast.
 */
final class FormToken
{
    public const VALID = 'valid';
    public const TOO_FAST = 'too_fast';
    public const EXPIRED = 'expired';
    public const INVALID = 'invalid';

    public function __construct(
        private readonly string $secret,
        private readonly int $minimumSeconds = 3,
        private readonly int $maximumSeconds = 7200,
    ) {
    }

    public function issue(?int $now = null): string
    {
        $time = (string) ($now ?? time());

        return $time . '.' . $this->sign($time);
    }

    public function check(string $token, ?int $now = null): string
    {
        $parts = explode('.', $token);
        if (count($parts) !== 2 || !ctype_digit($parts[0]) || !hash_equals($this->sign($parts[0]), $parts[1])) {
            return self::INVALID;
        }

        $age = ($now ?? time()) - (int) $parts[0];

        return match (true) {
            $age < $this->minimumSeconds => self::TOO_FAST,
            $age > $this->maximumSeconds => self::EXPIRED,
            default => self::VALID,
        };
    }

    private function sign(string $value): string
    {
        return hash_hmac('sha256', 'contact-form|' . $value, $this->secret);
    }
}
