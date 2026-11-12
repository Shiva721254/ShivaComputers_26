<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var string|null $portalUrl
 */
$phone = $page->business->phone;
$phoneLink = $phone !== null ? 'tel:' . preg_replace('/[^0-9+]/', '', $phone) : null;
?>
<section class="page-heading">
    <div class="wrap">
        <h1><?= e($t->text('remote.title')) ?></h1>
        <p class="lead"><?= e($t->text('remote.lead')) ?></p>
    </div>
</section>

<section class="section section--white">
    <div class="wrap">
        <div class="support-start">
<?php if ($portalUrl !== null): ?>
            <div>
                <h2><?= e($t->text('remote.start_title')) ?></h2>
                <p><?= e($t->text('remote.start_text')) ?></p>
            </div>
            <a class="button" href="<?= e($portalUrl) ?>"><?= e($t->text('remote.start_button')) ?></a>
<?php else: ?>
            <div>
                <h2><?= e($t->text('remote.call_title')) ?></h2>
                <p><?= e($t->text('remote.call_text')) ?></p>
            </div>
<?php if ($phoneLink !== null): ?>
            <a class="button" href="<?= e($phoneLink) ?>"><?= e($t->text('remote.call_button')) ?> <?= e($phone) ?></a>
<?php endif; ?>
<?php endif; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2 class="section-heading"><?= e($t->text('remote.steps_heading')) ?></h2>
        <ol class="steps">
<?php foreach ($t->items('remote.steps') as $step): ?>
            <li><h3><?= e($step['title']) ?></h3><p><?= e($step['text']) ?></p></li>
<?php endforeach; ?>
        </ol>
    </div>
</section>

<section class="section section--white">
    <div class="wrap">
        <h2 class="section-heading"><?= e($t->text('remote.windows_heading')) ?></h2>
        <p class="section-intro"><?= e($t->text('remote.windows_intro')) ?></p>
        <div class="table-scroll">
            <table class="os-table">
                <thead>
                    <tr>
                        <th scope="col"><?= e($t->text('remote.windows_col_version')) ?></th>
                        <th scope="col"><?= e($t->text('remote.windows_col_editions')) ?></th>
                        <th scope="col"><?= e($t->text('remote.windows_col_service')) ?></th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($t->items('remote.windows') as $row): ?>
                    <tr>
                        <th scope="row"><?= e($row['version']) ?></th>
                        <td><?= e($row['editions']) ?></td>
                        <td><?= e($row['service']) ?></td>
                    </tr>
<?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="note"><?= e($t->text('remote.windows_note')) ?></p>
    </div>
</section>

<section class="section">
    <div class="wrap split">
        <div>
            <h2><?= e($t->text('remote.can_heading')) ?></h2>
            <ul class="check-list">
<?php foreach ($t->items('remote.can') as $item): ?>
                <li><?= e($item['text']) ?></li>
<?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h2><?= e($t->text('remote.onsite_heading')) ?></h2>
            <ul class="check-list check-list--muted">
<?php foreach ($t->items('remote.onsite') as $item): ?>
                <li><?= e($item['text']) ?></li>
<?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>

<section class="section section--white">
    <div class="wrap">
        <h2 class="section-heading"><?= e($t->text('remote.safety_heading')) ?></h2>
        <div class="prose">
        <ol class="safety-list">
<?php foreach ($t->items('remote.safety') as $rule): ?>
            <li><?= e($rule['text']) ?></li>
<?php endforeach; ?>
        </ol>
        <p><?= e($t->text('remote.privacy_text')) ?> <a href="<?= e($page->url('/privacy')) ?>"><?= e($t->text('remote.privacy_link')) ?></a></p>
        </div>
    </div>
</section>
