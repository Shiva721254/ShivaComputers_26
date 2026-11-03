<section class="hero">
    <div class="wrap">
        <div class="hero-main">
            <p class="eyebrow"><?= e($t->text('home.eyebrow')) ?></p>
            <h1><?= e($t->text('home.title')) ?></h1>
            <p class="lead"><?= e($t->text('home.lead')) ?></p>
            <div class="actions">
                <a class="button" href="/<?= e($language) ?>/contact"><?= e($t->text('cta.button')) ?></a>
                <a class="text-link" href="/<?= e($language) ?>/portfolio"><?= e($t->text('home.portfolio_link')) ?></a>
            </div>
        </div>
        <dl class="hero-facts">
<?php foreach ($t->items('home.facts') as $fact): ?>
            <div><dt><?= e($fact['label']) ?></dt><dd><?= e($fact['value']) ?></dd></div>
<?php endforeach; ?>
        </dl>
    </div>
</section>

<section class="section section--white">
    <div class="wrap">
        <h2 class="section-heading"><?= e($t->text('services.heading')) ?></h2>
        <ul class="service-list">
<?php foreach ($t->items('services.items') as $service): ?>
            <li><h3><?= e($service['title']) ?></h3><p><?= e($service['text']) ?></p></li>
<?php endforeach; ?>
        </ul>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2 class="section-heading"><?= e($t->text('portfolio.heading')) ?></h2>
        <p class="section-intro"><?= e($t->text('portfolio.intro')) ?></p>
        <div class="project-grid">
<?php foreach (array_filter($projects, static fn (array $item): bool => $item['featured']) as $project): ?>
<?php require __DIR__ . '/../partials/project-card.php'; ?>
<?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--white">
    <div class="wrap">
        <h2 class="section-heading"><?= e($t->text('process.heading')) ?></h2>
        <ol class="steps">
<?php foreach ($t->items('process.items') as $step): ?>
            <li><h3><?= e($step['title']) ?></h3><p><?= e($step['text']) ?></p></li>
<?php endforeach; ?>
        </ol>
    </div>
</section>

<?php require __DIR__ . '/../partials/cta.php'; ?>
