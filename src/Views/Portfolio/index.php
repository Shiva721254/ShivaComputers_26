<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var list<App\Models\Project> $projects
 */
?>
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
            <?= $this->partial('Partials/project-card', compact('project', 'page', 't')) ?>
<?php endforeach; ?>
        </div>
    </div>
</section>

<?= $this->partial('Partials/cta', compact('page', 't')) ?>
