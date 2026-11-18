<?php

declare(strict_types=1);

namespace App\Framework;

use RuntimeException;

/**
 * The private storage folder next to public/. Deployments never overwrite or delete it,
 * so it keeps the secret key, rate-limit data and any message that could not be emailed.
 */
final class Storage
{
    public function __construct(private readonly string $directory)
    {
    }

    public function path(string $name): string
    {
        if (!is_dir($this->directory) && !mkdir($this->directory, 0700, true) && !is_dir($this->directory)) {
            throw new RuntimeException("Cannot create storage folder {$this->directory}.");
        }

        return $this->directory . '/' . $name;
    }

    /** The secret used to sign form tokens and hash IP addresses, created once. */
    public function secret(): string
    {
        $file = $this->path('app.key');
        if (!is_file($file)) {
            file_put_contents($file, bin2hex(random_bytes(32)), LOCK_EX);
            chmod($file, 0600);
        }

        return trim((string) file_get_contents($file));
    }
}
