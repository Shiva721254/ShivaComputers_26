<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    /** @param array<string, mixed> $config */
    public function __construct(
        private readonly string $directory,
        private readonly array $config,
    ) {
    }

    /** @param array<string, mixed> $data */
    public function render(string $template, array $data): string
    {
        $data['config'] = $this->config;
        $data['content'] = $this->capture($this->directory . '/pages/' . $template . '.php', $data);

        return $this->capture($this->directory . '/layout.php', $data);
    }

    /** @param array<string, mixed> $data */
    private function capture(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;

        return (string) ob_get_clean();
    }
}
