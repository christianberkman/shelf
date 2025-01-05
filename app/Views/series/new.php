<?php
$this->extend('layout');
$this->section('body');
echo match (session('alert')) {
    'error' => alert('Error', 'Could not add new series', 'danger'),
    default => null,
};
?>
<div class="row">
    <div class="col mb-3">
        <h1><?= bi('plus'); ?> Add new series</h1>

        <form method="post" action="<?= current_url(); ?>">
            <div class="row">
                <div class="col mb-3">
                    <label for="series_title" class="form-label">
                        Series Title
                    </label>
                    <input type="text" name="series_title" id="series_title" maxlength="128" value="<?= old('series_title') ?? $seriesTitle; ?>" class="form-control mb-3 <?= hasValidationError('series_title') ? 'is-invalid' : ''; ?>" pattern="[^;\\\/]+" required />
                    <div class="invalid-feedback">
                        <?= validationMessage('series_title'); ?>
                    </div>
                </div>

                <div class="col mb-3">
                    <p>
                        Series TItle should include the article at the end, e.g.:<br />
                        <ul>
                            <li>Wonderful Series, The</li>
                            <li>Guide to PHP, A</li>
                        </ul>
                    </p>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100">
                <?= bi('add'); ?> Add new series
            </button>
        </form>
    </div><!--/col-->
</div><!--/row-->
<?php $this->endSection(); ?>