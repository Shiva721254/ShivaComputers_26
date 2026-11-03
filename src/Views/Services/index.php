<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var list<App\Models\Offering> $offerings
 */
?>
<section class="page-heading">
    <div class="wrap">
        <h1><?= e($t->text('services.title')) ?></h1>
        <p class="lead"><?= e($t->text('services.lead')) ?></p>
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
        <h2 class="section-heading"><?= e($t->text('process.heading')) ?></h2>
        <?= $this->partial('Partials/process-steps', compact('t')) ?>
    </div>
</section>

<section class="section section--white">
    <div class="wrap prose">
        <h2><?= e($t->text('services.support_title')) ?></h2>
        <p><?= e($t->text('services.support_text')) ?></p>
    </div>
</section>

<?= $this->partial('Partials/cta', compact('page', 't')) ?>
