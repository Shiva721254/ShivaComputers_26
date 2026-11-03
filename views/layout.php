<?php
/** @var App\Core\Translator $t */
$routeSuffix = in_array($page, ['home', 'not-found'], true) ? '' : '/' . $page;
$otherLanguage = $language === 'nl' ? 'en' : 'nl';
$baseUrl = $config['base_url'];
$robots = $indexable && $config['content_approved'] ? 'index,follow' : 'noindex,follow';
$navigation = ['home' => '', 'services' => '/services', 'portfolio' => '/portfolio', 'about' => '/about', 'contact' => '/contact'];
?>
<!doctype html>
<html lang="<?= e($language) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($t->text('meta.' . $page . '.title')) ?></title>
    <meta name="description" content="<?= e($t->text('meta.' . $page . '.description')) ?>">
    <meta name="robots" content="<?= e($robots) ?>">
<?php if ($indexable): ?>
    <link rel="canonical" href="<?= e($baseUrl . '/' . $language . $routeSuffix) ?>">
    <link rel="alternate" hreflang="nl" href="<?= e($baseUrl . '/nl' . $routeSuffix) ?>">
    <link rel="alternate" hreflang="en" href="<?= e($baseUrl . '/en' . $routeSuffix) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= e($baseUrl . '/nl' . $routeSuffix) ?>">
<?php endif; ?>
    <link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/assets/css/site.css">
</head>
<body>
<a class="skip-link" href="#main"><?= e($t->text('nav.skip')) ?></a>
<?php if (!$config['content_approved']): ?>
<div class="preview-notice"><div class="wrap"><?= e($t->text('notice.preview')) ?></div></div>
<?php endif; ?>
<header class="site-header">
    <div class="wrap">
        <a class="brand" href="/<?= e($language) ?>">
            <img src="/assets/img/favicon.svg" alt="" width="32" height="32">
            <?= e($config['name']) ?>
        </a>
        <nav class="site-nav" aria-label="<?= e($t->text('nav.label')) ?>">
            <ul>
<?php foreach ($navigation as $key => $suffix): ?>
                <li><a href="/<?= e($language . $suffix) ?>"<?= $key === $page ? ' aria-current="page"' : '' ?>><?= e($t->text('nav.' . $key)) ?></a></li>
<?php endforeach; ?>
            </ul>
            <a class="lang-switch" href="/<?= e($otherLanguage . $routeSuffix) ?>" lang="<?= e($otherLanguage) ?>" hreflang="<?= e($otherLanguage) ?>"><?= e($t->text('nav.switch')) ?></a>
        </nav>
    </div>
</header>
<main id="main">
<?= $content ?>
</main>
<footer class="site-footer">
    <div class="wrap">
        <p><?= e($config['name']) ?> · <?= value_or_pending($config['contact']['address'], $t->text('field.address')) ?></p>
        <p>KVK <?= value_or_pending($config['contact']['kvk'], $t->text('field.kvk')) ?> · <?= e($t->text('field.vat_short')) ?> <?= value_or_pending($config['contact']['vat'], $t->text('field.vat')) ?></p>
        <a href="/<?= e($language) ?>/privacy"><?= e($t->text('nav.privacy')) ?></a>
    </div>
</footer>
</body>
</html>
