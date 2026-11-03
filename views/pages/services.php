<section class="page-heading">
    <div class="wrap">
        <h1><?= e($t->text('services.title')) ?></h1>
        <p class="lead"><?= e($t->text('services.lead')) ?></p>
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
        <h2 class="section-heading"><?= e($t->text('process.heading')) ?></h2>
        <ol class="steps">
<?php foreach ($t->items('process.items') as $step): ?>
            <li><h3><?= e($step['title']) ?></h3><p><?= e($step['text']) ?></p></li>
<?php endforeach; ?>
        </ol>
    </div>
</section>

<section class="section section--white">
    <div class="wrap prose">
        <h2><?= e($t->text('services.support_title')) ?></h2>
        <p><?= e($t->text('services.support_text')) ?></p>
    </div>
</section>

<?php require __DIR__ . '/../partials/cta.php'; ?>
