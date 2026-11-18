<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var array{token: string, sent: bool, old: array<string, string>, errors: array<string, string>, alert: ?string, honeypot: string} $form
 */
$business = $page->business;
$old = $form['old'];
$errors = $form['errors'];
?>
<section class="page-heading">
    <div class="wrap">
        <h1><?= e($t->text('contact.title')) ?></h1>
        <p class="lead"><?= e($t->text('contact.lead')) ?></p>
    </div>
</section>

<section class="section section--white">
    <div class="wrap split">
        <div id="contact-form">
            <h2><?= e($t->text('contact.form_heading')) ?></h2>
<?php if ($form['sent']): ?>
            <div class="form-alert form-alert--success" role="status" tabindex="-1">
                <p><strong><?= e($t->text('contact.sent_title')) ?></strong></p>
                <p><?= e($t->text('contact.sent_text')) ?></p>
            </div>
<?php else: ?>
<?php if ($form['alert'] !== null): ?>
            <div class="form-alert form-alert--error" role="alert"><p><?= e($t->text($form['alert'])) ?></p></div>
<?php endif; ?>
            <form class="contact-form" method="post" action="<?= e($page->url('/contact')) ?>#contact-form" novalidate>
                <input type="hidden" name="form_token" value="<?= e($form['token']) ?>">
                <div class="form-trap" aria-hidden="true">
                    <label for="<?= e($form['honeypot']) ?>"><?= e($t->text('contact.trap_label')) ?></label>
                    <input type="text" id="<?= e($form['honeypot']) ?>" name="<?= e($form['honeypot']) ?>" tabindex="-1" autocomplete="off">
                </div>
<?php foreach ([
    ['name', 'text', 'name', true],
    ['email', 'email', 'email', true],
    ['phone', 'tel', 'tel', false],
] as [$field, $type, $autocomplete, $required]): ?>
                <div class="form-field">
                    <label for="contact-<?= $field ?>"><?= e($t->text('contact.field.' . $field)) ?><?php if (!$required): ?> <span class="form-optional"><?= e($t->text('contact.optional')) ?></span><?php endif; ?></label>
                    <input type="<?= $type ?>" id="contact-<?= $field ?>" name="<?= $field ?>" value="<?= e($old[$field] ?? '') ?>" autocomplete="<?= $autocomplete ?>"<?= $required ? ' required' : '' ?><?php if (isset($errors[$field])): ?> aria-invalid="true" aria-describedby="contact-<?= $field ?>-error"<?php endif; ?>>
<?php if (isset($errors[$field])): ?>
                    <p class="form-error" id="contact-<?= $field ?>-error"><?= e($t->text($errors[$field])) ?></p>
<?php endif; ?>
                </div>
<?php endforeach; ?>
                <div class="form-field">
                    <label for="contact-message"><?= e($t->text('contact.field.message')) ?></label>
                    <textarea id="contact-message" name="message" rows="6" required<?php if (isset($errors['message'])): ?> aria-invalid="true" aria-describedby="contact-message-error"<?php endif; ?>><?= e($old['message'] ?? '') ?></textarea>
<?php if (isset($errors['message'])): ?>
                    <p class="form-error" id="contact-message-error"><?= e($t->text($errors['message'])) ?></p>
<?php endif; ?>
                </div>
                <p class="form-note"><?= e($t->text('contact.privacy_note')) ?> <a href="<?= e($page->url('/privacy')) ?>"><?= e($t->text('contact.privacy_link')) ?></a></p>
                <button type="submit" class="button"><?= e($t->text('contact.send')) ?></button>
            </form>
<?php endif; ?>
        </div>
        <div>
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
    </div>
</section>
