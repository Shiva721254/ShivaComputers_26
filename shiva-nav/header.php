<?php
/**
 * Shared Shiva Computers top bar. Identical in all three sites (source of truth: ShivaComputers_26/shiva-nav/).
 *
 * Expects $shivaNav: the array returned by ShivaNav\Navigation::build().
 * Needs /assets/shiva-nav/shiva-nav.css and /assets/shiva-nav/logo.svg. No JavaScript: dropdowns are <details>.
 *
 * @var array<string, mixed> $shivaNav
 */
// Runs in its own scope, so it can never overwrite variables of the page that includes it.
(static function (array $shivaNav): void {
$h = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$current = static fn (array $link): string => $link['current'] ? ' aria-current="page"' : '';
$languages = $shivaNav['languages'];
?>
<div class="snav" data-snav-version="<?= $h($shivaNav['version']) ?>">
    <div class="snav-inner">
        <a class="snav-brand" href="<?= $h($shivaNav['homeUrl']) ?>" aria-label="<?= $h($shivaNav['labels']['home']) ?>">
            <img src="/assets/shiva-nav/logo.svg" alt="" width="32" height="32">
            <span>Shiva Computers</span>
        </a>
        <nav class="snav-main" aria-label="<?= $h($shivaNav['labels']['navigation']) ?>">
            <ul class="snav-list">
<?php foreach ($shivaNav['items'] as $item): ?>
<?php if ($item['children'] === []): ?>
                <li><a class="snav-link<?= $item['active'] ? ' is-active' : '' ?>" href="<?= $h($item['url']) ?>"<?= $current($item) ?>><?= $h($item['label']) ?></a></li>
<?php else: ?>
                <li class="snav-group">
                    <details class="snav-dropdown" name="snav">
                        <summary class="snav-link<?= $item['active'] ? ' is-active' : '' ?>"><?= $h($item['label']) ?></summary>
                        <ul class="snav-panel">
<?php foreach ($item['children'] as $child): ?>
                            <li><a href="<?= $h($child['url']) ?>"<?= $current($child) ?>><strong><?= $h($child['label']) ?><?= $child['external'] ? '<span class="snav-ext" aria-hidden="true"> &#8599;</span>' : '' ?></strong><span><?= $h((string) $child['description']) ?></span></a></li>
<?php endforeach; ?>
                        </ul>
                    </details>
                </li>
<?php endif; ?>
<?php endforeach; ?>
            </ul>
        </nav>
<?php if (count($languages) === 1): ?>
        <a class="snav-lang" href="<?= $h($languages[0]['url']) ?>" hreflang="<?= $h($languages[0]['code']) ?>" lang="<?= $h($languages[0]['code']) ?>" title="<?= $h($languages[0]['title']) ?>"><?= $h($languages[0]['name']) ?></a>
<?php elseif (count($languages) > 1): ?>
        <details class="snav-dropdown snav-langs" name="snav">
            <summary class="snav-lang"><?= $h($shivaNav['labels']['languages']) ?></summary>
            <ul class="snav-panel snav-panel--right">
<?php foreach ($languages as $language): ?>
                <li><a href="<?= $h($language['url']) ?>" hreflang="<?= $h($language['code']) ?>" lang="<?= $h($language['code']) ?>" title="<?= $h($language['title']) ?>"><strong><?= $h($language['name']) ?></strong></a></li>
<?php endforeach; ?>
            </ul>
        </details>
<?php endif; ?>
        <details class="snav-mobile">
            <summary><?= $h($shivaNav['labels']['menu']) ?></summary>
            <nav class="snav-mobile-panel" aria-label="<?= $h($shivaNav['labels']['navigation']) ?>">
                <ul>
<?php foreach ($shivaNav['items'] as $item): ?>
<?php if ($item['children'] === []): ?>
                    <li><a href="<?= $h($item['url']) ?>"<?= $current($item) ?>><?= $h($item['label']) ?></a></li>
<?php else: ?>
                    <li class="snav-mobile-group">
                        <span><?= $h($item['label']) ?></span>
                        <ul>
<?php foreach ($item['children'] as $child): ?>
                            <li><a href="<?= $h($child['url']) ?>"<?= $current($child) ?>><?= $h($child['label']) ?></a></li>
<?php endforeach; ?>
                        </ul>
                    </li>
<?php endif; ?>
<?php endforeach; ?>
<?php foreach ($languages as $language): ?>
                    <li><a class="snav-mobile-lang" href="<?= $h($language['url']) ?>" hreflang="<?= $h($language['code']) ?>" lang="<?= $h($language['code']) ?>"><?= $h($language['name']) ?></a></li>
<?php endforeach; ?>
                </ul>
            </nav>
        </details>
    </div>
</div>
<?php
})($shivaNav);
