<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ContactMessage;
use App\Services\Interfaces\IMailer;

/**
 * Writes messages to a private folder instead of sending them. Used during local development,
 * and as a safety net when real sending fails.
 */
final class FileMailer implements IMailer
{
    public function __construct(
        private readonly string $directory,
        private readonly MailFormatter $formatter,
    ) {
    }

    public function send(ContactMessage $message): bool
    {
        if (!is_dir($this->directory) && !mkdir($this->directory, 0700, true) && !is_dir($this->directory)) {
            return false;
        }

        $file = sprintf('%s/%s-%s.txt', $this->directory, date('Ymd-His'), bin2hex(random_bytes(4)));
        $headers = '';
        foreach ($this->formatter->headers($message) as $name => $value) {
            $headers .= "{$name}: {$value}\r\n";
        }

        return file_put_contents($file, $headers . 'Subject: ' . $this->formatter->subject($message) . "\r\n\r\n" . $this->formatter->body($message), LOCK_EX) !== false;
    }
}
