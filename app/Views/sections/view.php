<?php
$this->extend('layout');
$this->section('body');
?>
<?= match (session('alert')) {
    'success' => alert('Success', 'Saved section details', 'success'),
    'error'   => alert('Error', 'Could not save section details', 'danger'),
    default   => null,
};
?>


<div class="row">
    <div class="col mb-3">
        <h1><?= "{$section->name} ({$section->section_id})"; ?></h1>
    </div><!--/col-->
</div><!--/row-->

<form method="post" action="<?= current_url(); ?>">
    <div class="row">
        <div class="col-lg-4 mb-3">
            <label for="section_id" class="form-label">
                Section ID
            </label>

            <input type="text" id="section_id" class="form-control" value="<?= $section->section_id; ?>" disabled />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-3">
            <label for="name" class="form-label">
                Section Name
            </label>

            <input type="text" id="name" name="name" class="form-control" value="<?= $section->name; ?>" />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-3">
            <label for="note" class="form-label">
                Note
            </label>

            <textarea id="note" name="note" class="form-control w-100" rows="5"><?= nl2br($section->note); ?></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col=lg=4 mb-4">
            <button type="submit" class="btn btn-primary">
                <?= bi('check'); ?> Save Changes
            </button>
        </div>
    </div>
</form>


<?php $this->endSection(); ?>