<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 */
?>
<section class="page-heading">
    <div class="wrap">
        <h1><?= e($t->text('about.title')) ?></h1>
        <p class="lead"><?= e($t->text('about.lead')) ?></p>
    </div>
</section>

<section class="section section--white">
    <div class="wrap prose">
<?php foreach ($t->items('about.paragraphs') as $paragraph): ?>
        <p><?= e($paragraph['text']) ?></p>
<?php endforeach; ?>
    </div>
</section>

<?= $this->partial('Partials/cta', compact('page', 't')) ?>
