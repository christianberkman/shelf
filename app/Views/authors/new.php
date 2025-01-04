<?php
$this->extend('layout');
$this->section('body');
echo match (session('alert')) {
    'error' => alert('Error', 'Could not add new author', 'danger'),
    default => null,
};
?>
<div class="row">
    <div class="col mb-3">
        <h1><?= bi('plus'); ?> Add new author</h1>

        <form method="post" action="<?= current_url(); ?>">
            <div class="row">
                <div class="col mb-3">
                    <label for="name" class="form-label">
                        Author's Name
                    </label>
                    <input type="text" name="name" id="name" maxlength="128" value="<?= old('name'); ?>" class="form-control mb-3 <?= hasValidationError('name') ? 'is-invalid' : ''; ?>" pattern="[^;\\\/]+" required />
                    <div class="invalid-feedback">
                        <?= validationMessage('name'); ?>
                    </div>
                </div>

                <div class="col mb-3">
                    <p>
                        Author's name should be formatted as <em>Surname, INITIALS</em> or <em>Surname / Publisher</em>:<br />
                        <ul>
                            <li>Shakespeare, W.</li>
                            <li>Lewis, C.S.</li>
                            <li>Random House</li>
                        </ul>
                    </p>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100">
                <?= bi('add'); ?> Add new author
            </button>
        </form>
    </div><!--/col-->
</div><!--/row-->
<?php $this->endSection(); ?>