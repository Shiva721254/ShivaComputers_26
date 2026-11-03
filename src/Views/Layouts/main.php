<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var string $content
 */
?>
<!doctype html>
<html lang="<?= e($page->language->value) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page->title) ?></title>
    <meta name="description" content="<?= e($page->description) ?>">
    <meta name="robots" content="<?= e($page->robots) ?>">
<?php if ($page->canonicalUrl !== null): ?>
    <link rel="canonical" href="<?= e($page->canonicalUrl) ?>">
<?php endif; ?>
<?php foreach ($page->alternateUrls as $hreflang => $url): ?>
    <link rel="alternate" hreflang="<?= e($hreflang) ?>" href="<?= e($url) ?>">
<?php endforeach; ?>
    <link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/assets/css/site.css">
</head>
<body>
<a class="skip-link" href="#main"><?= e($t->text('nav.skip')) ?></a>
<?php if ($page->showPreviewNotice): ?>
<div class="preview-notice"><div class="wrap"><?= e($t->text('notice.preview')) ?></div></div>
<?php endif; ?>
<header class="site-header">
    <div class="wrap">
        <a class="brand" href="<?= e($page->url()) ?>">
            <img src="/assets/img/favicon.svg" alt="" width="32" height="32">
            <?= e($page->business->name) ?>
        </a>
        <nav class="site-nav" aria-label="<?= e($t->text('nav.label')) ?>">
            <ul>
<?php foreach ($page->navigation as $item): ?>
                <li><a href="<?= e($item->url) ?>"<?= $item->isCurrent ? ' aria-current="page"' : '' ?>><?= e($item->label) ?></a></li>
<?php endforeach; ?>
            </ul>
            <a class="lang-switch" href="<?= e($page->languageSwitchUrl) ?>" lang="<?= e($page->otherLanguage->value) ?>" hreflang="<?= e($page->otherLanguage->value) ?>"><?= e($t->text('nav.switch')) ?></a>
        </nav>
    </div>
</header>
<main id="main">
<?= $content ?>
</main>
<footer class="site-footer">
    <div class="wrap">
        <p><?= e($page->business->name) ?> · <?= value_or_pending($page->business->address, $t->text('field.address')) ?></p>
        <p>KVK <?= value_or_pending($page->business->kvk, $t->text('field.kvk')) ?> · <?= e($t->text('field.vat_short')) ?> <?= value_or_pending($page->business->vat, $t->text('field.vat')) ?></p>
        <a href="<?= e($page->url('/privacy')) ?>"><?= e($t->text('nav.privacy')) ?></a>
    </div>
</footer>
</body>
</html>
