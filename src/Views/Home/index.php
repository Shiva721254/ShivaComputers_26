<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var list<App\Models\Offering> $offerings
 * @var list<App\Models\Project> $projects
 */
?>
<section class="hero">
    <div class="wrap">
        <div class="hero-main">
            <p class="eyebrow"><?= e($t->text('home.eyebrow')) ?></p>
            <h1><?= e($t->text('home.title')) ?></h1>
            <p class="lead"><?= e($t->text('home.lead')) ?></p>
            <div class="actions">
                <a class="button" href="<?= e($page->url('/contact')) ?>"><?= e($t->text('cta.button')) ?></a>
                <a class="text-link" href="<?= e($page->url('/portfolio')) ?>"><?= e($t->text('home.portfolio_link')) ?></a>
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
        <?= $this->partial('Partials/offering-list', ['offerings' => $offerings]) ?>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2 class="section-heading"><?= e($t->text('portfolio.heading')) ?></h2>
        <p class="section-intro"><?= e($t->text('portfolio.intro')) ?></p>
        <div class="project-grid">
<?php foreach ($projects as $project): ?>
            <?= $this->partial('Partials/project-card', compact('project', 'page', 't')) ?>
<?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--white">
    <div class="wrap">
        <h2 class="section-heading"><?= e($t->text('process.heading')) ?></h2>
        <?= $this->partial('Partials/process-steps', compact('t')) ?>
    </div>
</section>

<?= $this->partial('Partials/cta', compact('page', 't')) ?>
