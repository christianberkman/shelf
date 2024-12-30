<?php $this->extend('layout'); ?>
<?php $this->section('body'); ?>
<div class="row">
    <div class="col mb-3">
        <h1><i class="bi bi-collection"></i> Sections</h1>
    </div>
</div><!--/row-->

<div class="row row-cols-4">
    <?php foreach($sections as $section): ?>
    <div class="col mb-3">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-heading text-center">
                    <?=$section->name; ?>
                </h5>
                <p class="text-center"><?=bookCount($section->section_id); ?> books, <?=copyCount($section->section_id); ?> copies</p>

                <a href="<?=site_url("/sections/{$section->section_id}/browse"); ?>" class="btn btn-outline-primary w-100 mb-3">
                    <i class="bi bi-search"></i> Browse
                </a>

                <a href="<?=site_url("/sections/{$section->section_id}"); ?>" class="btn btn-outline-secondary w-100 mb-3">
                    <i class="bi bi-gear"></i> Manage
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col-->
    <?php endforeach; ?>
</div><!--row-cols-->

<?php $this->endSection(); ?>