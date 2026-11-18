<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Interfaces\IRateLimiter;

/**
 * Allows a few submissions per visitor per hour. IP addresses are stored only as a keyed hash
 * and are forgotten after the time window, so no readable IP address is ever kept.
 */
final class FileRateLimiter implements IRateLimiter
{
    public function __construct(
        private readonly string $file,
        private readonly string $secret,
        private readonly int $maxAttempts = 5,
        private readonly int $windowSeconds = 3600,
    ) {
    }

    public function attempt(string $visitor): bool
    {
        $key = hash_hmac('sha256', 'rate|' . $visitor, $this->secret);
        $now = time();

        $handle = fopen($this->file, 'c+');
        if ($handle === false) {
            return true; // Never block a real visitor because the limit file is unavailable.
        }

        try {
            flock($handle, LOCK_EX);
            $data = json_decode((string) stream_get_contents($handle), true);
            $data = is_array($data) ? $data : [];

            // Forget everything outside the time window.
            foreach ($data as $hash => $times) {
                $data[$hash] = array_values(array_filter((array) $times, fn ($t): bool => is_int($t) && $t > $now - $this->windowSeconds));
                if ($data[$hash] === []) {
                    unset($data[$hash]);
                }
            }

            $allowed = count($data[$key] ?? []) < $this->maxAttempts;
            if ($allowed) {
                $data[$key][] = $now;
            }

            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, (string) json_encode($data));

            return $allowed;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}
