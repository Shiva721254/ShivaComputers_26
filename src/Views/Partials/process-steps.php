<?php /** @var App\Framework\PageTranslator $t */ ?>
<ol class="steps">
<?php foreach ($t->items('process.items') as $step): ?>
    <li><h3><?= e($step['title']) ?></h3><p><?= e($step['text']) ?></p></li>
<?php endforeach; ?>
</ol>
