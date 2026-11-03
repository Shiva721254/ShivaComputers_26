<?php /** @var App\Framework\PageTranslator $t */ ?>
<section class="page-heading">
    <div class="wrap">
        <h1><?= e($t->text('privacy.title')) ?></h1>
        <p class="lead"><?= e($t->text('privacy.lead')) ?></p>
    </div>
</section>

<section class="section section--white">
    <div class="wrap prose">
<?php foreach ($t->items('privacy.sections') as $section): ?>
        <h2><?= e($section['title']) ?></h2>
        <p><?= e($section['text']) ?></p>
<?php endforeach; ?>
    </div>
</section>
