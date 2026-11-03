<article class="project">
<?php if ($config['env'] === 'development'): ?>
    <div class="screenshot-placeholder" aria-hidden="true"><?= e($t->text('portfolio.placeholder')) ?></div>
<?php endif; ?>
    <p class="project-label"><?= e($project['label'][$language]) ?></p>
    <h3><?= e($project['name']) ?></h3>
    <p><?= e($project['description'][$language]) ?></p>
    <p class="project-stack"><?= e(implode(' · ', $project['stack'])) ?></p>
    <a href="<?= e($project['repository']) ?>" rel="noopener noreferrer"><?= e($t->text('portfolio.code')) ?><span class="visually-hidden"><?= e(': ' . $project['name']) ?></span></a>
</article>
