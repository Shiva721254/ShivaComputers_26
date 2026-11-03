<?php

declare(strict_types=1);

/** Escapes text for safe output in HTML content and attributes. */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Shows a configured value, or a visible placeholder when it is not set yet. */
function value_or_pending(?string $value, string $placeholder): string
{
    return $value !== null && $value !== ''
        ? e($value)
        : '<span class="pending">[' . e($placeholder) . ']</span>';
}
