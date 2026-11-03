<?php /** @var list<App\Models\Offering> $offerings */ ?>
<ul class="service-list">
<?php foreach ($offerings as $offering): ?>
    <li><h3><?= e($offering->title) ?></h3><p><?= e($offering->description) ?></p></li>
<?php endforeach; ?>
</ul>
