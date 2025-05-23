<?php $this->extend('layout'); ?>
<?php $this->section('body'); ?>
<?= match (session('alert')) {
    'delete-success' => alert('Success', 'Successfully deleted section ' . session('sectionId'), 'success'),
    default          => null,
};
?>

<div class="row">
    <div class="col mb-3">
        <h1><?= bi('collection'); ?> Sections</h1>
    </div>
</div><!--/row-->

<div class="row row-cols-4">
    <?php foreach ($sections as $section): ?>
        <div class="col mb-3">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-heading text-center">
                        <?= $section->name; ?>
                    </h5>
                    <p class="text-center"><?= bookCount($section->section_id); ?> books, <?= copyCount($section->section_id); ?> copies</p>

                    <a href="<?= site_url("/books/browse/?section_id={$section->section_id}"); ?>" class="btn btn-outline-primary w-100 mb-3">
                        <?= bi('search'); ?> Browse
                    </a>

                    <a href="<?= site_url("/sections/{$section->section_id}"); ?>" class="btn btn-outline-secondary w-100 mb-3">
                        <?= bi('manage'); ?> Manage
                    </a>
                </div>
            </div><!--/card-->
        </div><!--/col-->
    <?php endforeach; ?>
</div><!--row-cols-->

<div class="row">
    <div class="col mb-3">
        <a href="<?= site_url('sections/new'); ?>" class="btn btn-outline-success btn-lg w-100">
            <?= bi('add'); ?> Add a section
        </a>
    </div><!--/col-->
</div><!--/row-->

<?php $this->endSection(); ?>