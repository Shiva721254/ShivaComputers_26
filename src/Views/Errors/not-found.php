<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 */
?>
<section class="page-heading">
    <div class="wrap">
        <h1><?= e($t->text('not_found.title')) ?></h1>
        <p class="lead"><?= e($t->text('not_found.text')) ?></p>
        <div class="actions">
            <a class="button" href="<?= e($page->url()) ?>"><?= e($t->text('not_found.link')) ?></a>
        </div>
    </div>
</section>
