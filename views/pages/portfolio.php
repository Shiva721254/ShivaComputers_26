<section class="page-heading">
    <div class="wrap">
        <h1><?= e($t->text('portfolio.title')) ?></h1>
        <p class="lead"><?= e($t->text('portfolio.intro')) ?></p>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="project-grid">
<?php foreach ($projects as $project): ?>
<?php require __DIR__ . '/../partials/project-card.php'; ?>
<?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../partials/cta.php'; ?>
