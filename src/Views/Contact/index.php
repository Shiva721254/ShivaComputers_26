<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 */
$business = $page->business;
?>
<section class="page-heading">
    <div class="wrap">
        <h1><?= e($t->text('contact.title')) ?></h1>
        <p class="lead"><?= e($t->text('contact.lead')) ?></p>
    </div>
</section>

<section class="section section--white">
    <div class="wrap">
        <h2><?= e($t->text('contact.details')) ?></h2>
        <dl class="contact-details">
            <div>
                <dt><?= e($t->text('field.email')) ?></dt>
                <dd>
<?php if ($business->email): ?>
                    <a href="mailto:<?= e($business->email) ?>"><?= e($business->email) ?></a>
<?php else: ?>
                    <?= value_or_pending(null, $t->text('field.email')) ?>

<?php endif; ?>
                </dd>
            </div>
            <div>
                <dt><?= e($t->text('field.phone')) ?></dt>
                <dd><?= value_or_pending($business->phone, $t->text('field.phone')) ?></dd>
            </div>
            <div>
                <dt><?= e($t->text('field.availability')) ?></dt>
                <dd><?= e($t->text('contact.availability')) ?></dd>
            </div>
            <div>
                <dt><?= e($t->text('field.address')) ?></dt>
                <dd><?= value_or_pending($business->address, $t->text('field.address')) ?></dd>
            </div>
            <div>
                <dt>KVK</dt>
                <dd><?= value_or_pending($business->kvk, $t->text('field.kvk')) ?></dd>
            </div>
        </dl>
    </div>
</section>
