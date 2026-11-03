<?php

declare(strict_types=1);

namespace App\Framework;

final class View
{
    public function __construct(private readonly string $directory)
    {
    }

    /** @param array<string, mixed> $data */
    public function render(string $template, array $data, string $layout = 'Layouts/main'): string
    {
        $data['content'] = $this->partial($template, $data);

        return $this->partial($layout, $data);
    }

    /** @param array<string, mixed> $data */
    public function partial(string $template, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $this->directory . '/' . $template . '.php';

        return (string) ob_get_clean();
    }
}
