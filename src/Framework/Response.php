<?php

declare(strict_types=1);

namespace App\Framework;

/** Controllers return a Response instead of echoing, so tests can inspect the result. */
final readonly class Response
{
    /** @param array<string, string> $headers */
    public function __construct(
        public string $body = '',
        public int $status = 200,
        public array $headers = [],
    ) {
    }

    public static function html(string $body, int $status = 200): self
    {
        return new self($body, $status, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    public static function text(string $body, int $status, array $headers = []): self
    {
        return new self($body, $status, ['Content-Type' => 'text/plain; charset=utf-8'] + $headers);
    }

    public static function redirect(string $location, int $status = 302): self
    {
        return new self('', $status, ['Location' => $location]);
    }

    /** @param array<string, string> $headers */
    public function withHeaders(array $headers): self
    {
        return new self($this->body, $this->status, $this->headers + $headers);
    }

    public function send(): void
    {
        header_remove('X-Powered-By');
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
        echo $this->body;
    }
}
