<?php $email = $config['contact']['email']; ?>
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
<?php if ($email): ?>
                    <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
<?php else: ?>
                    <?= value_or_pending(null, $t->text('field.email')) ?>

<?php endif; ?>
                </dd>
            </div>
            <div>
                <dt><?= e($t->text('field.phone')) ?></dt>
                <dd><?= value_or_pending($config['contact']['phone'], $t->text('field.phone')) ?></dd>
            </div>
            <div>
                <dt><?= e($t->text('field.address')) ?></dt>
                <dd><?= value_or_pending($config['contact']['address'], $t->text('field.address')) ?></dd>
            </div>
            <div>
                <dt>KVK</dt>
                <dd><?= value_or_pending($config['contact']['kvk'], $t->text('field.kvk')) ?></dd>
            </div>
        </dl>
    </div>
</section>
