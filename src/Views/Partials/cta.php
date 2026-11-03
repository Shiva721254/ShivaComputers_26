<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 */
?>
<section class="cta">
    <div class="wrap">
        <div>
            <h2><?= e($t->text('cta.title')) ?></h2>
            <p><?= e($t->text('cta.text')) ?></p>
        </div>
        <a class="button" href="<?= e($page->url('/contact')) ?>"><?= e($t->text('cta.button')) ?></a>
    </div>
</section>
