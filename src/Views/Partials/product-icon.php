<?php
/**
 * Inline line icon per product. Inline SVG needs no extra request and is allowed by the Content Security Policy.
 * @var App\Enums\Product $product
 */
$paths = match ($product) {
    App\Enums\Product::Software => '<path d="m8 8-5 4 5 4M16 8l5 4-5 4M13.5 5l-3 14"/>',
    App\Enums\Product::Rescue => '<rect x="7" y="2" width="10" height="7" rx="1"/><path d="M10 5h.01M14 5h.01"/><path d="M6 9h12v6a6 6 0 0 1-12 0z"/><path d="m10 14 1.5 1.5L14 13"/>',
    App\Enums\Product::Assist => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/><path d="m8.5 10 2 2 4-4"/>',
    App\Enums\Product::RemoteSupport => '<path d="M5 12.5a10 10 0 0 1 14 0M8.5 16a5 5 0 0 1 7 0M2 9a14.5 14.5 0 0 1 20 0"/><circle cx="12" cy="19" r="1"/>',
};
?>
<svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><?= $paths ?></svg>
