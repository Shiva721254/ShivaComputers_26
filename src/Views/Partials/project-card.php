<?php
/**
 * @var App\Models\Project $project
 * @var App\ViewModels\PageViewModel $page
 * @var App\Framework\PageTranslator $t
 */
?>
<article class="project">
<?php if ($project->image !== null): ?>
    <a class="project-image-link" href="<?= e($project->image) ?>">
        <img class="project-image" src="<?= e($project->image) ?>" alt="<?= e($project->imageAlt) ?>" width="1280" height="800" loading="lazy">
        <span class="visually-hidden"><?= e($t->text('portfolio.image_open')) ?></span>
    </a>
<?php elseif ($page->showScreenshotPlaceholders): ?>
    <div class="screenshot-placeholder" aria-hidden="true"><?= e($t->text('portfolio.placeholder')) ?></div>
<?php endif; ?>
    <p class="project-label"><?= e($project->label) ?></p>
    <h3><?= e($project->name) ?></h3>
    <p><?= e($project->description) ?></p>
    <p class="project-stack"><?= e(implode(' · ', $project->stack)) ?></p>
    <a href="<?= e($project->repositoryUrl) ?>" rel="noopener noreferrer"><?= e($t->text('portfolio.code')) ?><span class="visually-hidden"><?= e(': ' . $project->name) ?></span></a>
</article>
