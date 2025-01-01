<div class="alert alert-<?=$class ?? 'primary'; ?>">
<?php if(! empty($heading)): ?>
    <strong><?=$heading; ?></strong>&nbsp;&mdash;
<?php endif; ?>
    <?=$body; ?>
</div>