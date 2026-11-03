<?php

declare(strict_types=1);

namespace App\Framework;

use App\Enums\Language;

/** Gives views a short $t->text('key') call for the language of the current page. */
final readonly class PageTranslator
{
    public function __construct(
        private Translator $translator,
        private Language $language,
    ) {
    }

    public function text(string $key): string
    {
        return $this->translator->text($this->language, $key);
    }

    /** @return list<array<string, string>> */
    public function items(string $key): array
    {
        return $this->translator->items($this->language, $key);
    }
}
