<?php

$pager->setSurroundCount(2);
?>

<div class="row justify-content-center">
	<div class="col-auto">
		<a href="<?=$pager->getFirst(); ?>" class="btn btn-sm btn-secondary"><?=bi('first'); ?></a>
	</div>

	<?php if($pager->hasPrevious()): ?>
	<div class="col-auto">
		<a href="<?=$pager->getPrevious(); ?>" class="btn btn-sm btn-secondary">
			<?=bi('prev'); ?>
		</a>
	</div>
	<?php endif; ?>


	<?php foreach($pager->links() as $link): ?>
	<div class="col-auto">
		<a href="<?=$link['uri']; ?>" class="btn btn-sm <?=($link['active'] ? 'btn-primary' : 'btn-secondary'); ?>">
			<?=$link['title']; ?>
		</a>
	</div>
	<?php endforeach; ?>

	<?php if($pager->hasNext()): ?>
	<div class="col-auto">
		<a href="<?=$pager->getNext(); ?>" class="btn btn-sm btn-secondary">
			<?=bi('next'); ?>
		</a>
	</div>
	<?php endif; ?>

	<div class="col-auto">
		<a href="<?=$pager->getLast(); ?>" class="btn btn-sm btn-secondary"><?=bi('last'); ?></a>
	</div>
</div><!--/pager-row-->