<?php
/**
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 * @var list<App\Models\Offering> $offerings
 * @var list<App\Models\Project> $projects
 * @var list<App\ViewModels\ProductCard> $products
 */
?>
<section class="hero">
    <div class="wrap">
        <div class="hero-main">
            <p class="eyebrow"><?= e($t->text('home.eyebrow')) ?></p>
            <h1><?= e($t->text('home.title')) ?></h1>
            <p class="lead"><?= e($t->text('home.lead')) ?></p>
            <div class="actions">
                <a class="button" href="<?= e($page->url('/contact')) ?>"><?= e($t->text('cta.button')) ?></a>
                <a class="text-link" href="<?= e($page->url('/portfolio')) ?>"><?= e($t->text('home.portfolio_link')) ?></a>
            </div>
        </div>
        <dl class="hero-facts">
<?php foreach ($t->items('home.facts') as $fact): ?>
            <div><dt><?= e($fact['label']) ?></dt><dd><?= e($fact['value']) ?></dd></div>
<?php endforeach; ?>
        </dl>
    </div>
</section>

<section class="section products" aria-labelledby="products-heading">
    <div class="wrap">
        <h2 class="section-heading" id="products-heading"><?= e($t->text('products.heading')) ?></h2>
        <p class="section-intro"><?= e($t->text('products.intro')) ?></p>
        <ul class="product-grid">
<?php foreach ($products as $number => $card): ?>
            <li>
                <a class="product-card product-card--<?= e($card->product->value) ?>" href="<?= e($card->url) ?>">
                    <span class="product-top">
                        <span class="product-icon"><?= $this->partial('Partials/product-icon', ['product' => $card->product]) ?></span>
                        <span class="product-number" aria-hidden="true"><?= e(sprintf('%02d', $number + 1)) ?></span>
                    </span>
                    <span class="product-tag"><?= e($card->tag) ?></span>
                    <h3><?= e($card->title) ?></h3>
                    <p><?= e($card->text) ?></p>
                    <span class="product-link"><?= e($card->linkText) ?><span aria-hidden="true"> <?= $card->isExternal ? '&#8599;' : '&#8594;' ?></span></span>
                </a>
            </li>
<?php endforeach; ?>
        </ul>
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
        <h2 class="section-heading"><?= e($t->text('portfolio.heading')) ?></h2>
        <p class="section-intro"><?= e($t->text('portfolio.intro')) ?></p>
        <div class="project-grid">
<?php foreach ($projects as $project): ?>
            <?= $this->partial('Partials/project-card', compact('project', 'page', 't')) ?>
<?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--white">
    <div class="wrap">
        <h2 class="section-heading"><?= e($t->text('process.heading')) ?></h2>
        <?= $this->partial('Partials/process-steps', compact('t')) ?>
    </div>
</section>

<?= $this->partial('Partials/cta', compact('page', 't')) ?>
