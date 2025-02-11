<?php
$this->extend('layout');
$this->section('body');
?>
<?= match (session('alert')) {
    'duplicate' => alert('Error', 'A section with this name or ID already exists', 'danger'),
    'error'     => alert('Error', 'Could not add section <em>' . session('error') . '</em>', 'danger'),
    default     => null,
};
?>


<div class="row">
    <div class="col mb-3">
        <h1><?= bi('add'); ?> Add Section</h1>
    </div><!--/col-->
</div><!--/row-->

<form method="post" action="<?= current_url(); ?>">
    <div class="row">
        <div class="col-lg-4 mb-3">
            <label for="name" class="form-label">
                Section ID
            </label>

            <input type="text" id="section_id" name="section_id" class="form-control" value="<?= old('section_id'); ?>" required />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-3">
            <label for="name" class="form-label">
                Section Name
            </label>

            <input type="text" id="name" name="name" class="form-control" value="<?= old('name'); ?>" required />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-3">
            <label for="note" class="form-label">
                Note
            </label>

            <textarea id="note" name="note" class="form-control w-100" rows="5"><?= old('note'); ?></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col=lg=4 mb-4">
            <button type="submit" class="btn btn-primary">
                <?= bi('plus'); ?> Add section
            </button>
        </div>
    </div>
</form>


<?php $this->endSection(); ?>