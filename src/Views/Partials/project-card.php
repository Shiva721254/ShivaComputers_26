<?php
/**
 * @var App\Models\Project $project
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 */
?>
<article class="project">
<?php if ($page->showScreenshotPlaceholders): ?>
    <div class="screenshot-placeholder" aria-hidden="true"><?= e($t->text('portfolio.placeholder')) ?></div>
<?php endif; ?>
    <p class="project-label"><?= e($project->label) ?></p>
    <h3><?= e($project->name) ?></h3>
    <p><?= e($project->description) ?></p>
    <p class="project-stack"><?= e(implode(' · ', $project->stack)) ?></p>
    <a href="<?= e($project->repositoryUrl) ?>" rel="noopener noreferrer"><?= e($t->text('portfolio.code')) ?><span class="visually-hidden"><?= e(': ' . $project->name) ?></span></a>
</article>
