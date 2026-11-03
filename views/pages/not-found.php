<section class="page-heading">
    <div class="wrap">
        <h1><?= e($t->text('not_found.title')) ?></h1>
        <p class="lead"><?= e($t->text('not_found.text')) ?></p>
        <div class="actions">
            <a class="button" href="/<?= e($language) ?>"><?= e($t->text('not_found.link')) ?></a>
        </div>
    </div>
</section>
