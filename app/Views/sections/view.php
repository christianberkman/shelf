<?php
$this->extend('layout');
$this->section('body');
?>
<div class="row">
    <div class="col mb-3">
        <h1><?= "{$section->name} ({$section->section_id})"; ?></h1>
    </div><!--/col-->
</div><!--/row-->
<?php $this->endSection(); ?>