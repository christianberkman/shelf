<?php
$this->extend('layout');
$this->section('body');
?>
<?= match (session('alert')) {
    'success'        => alert('Success', 'Saved section details', 'success'),
    'insert-success' => alert('Success', 'Added new section', 'success'),
    'not-empty'      => alert('Error', 'Could not delete this section because the section has books', 'danger'),
    'error'          => alert('Error', 'Could not save section details', 'danger'),
    'delete-error'   => alert('Error', 'Could not delete section', 'danger'),
    default          => null,
};
?>


<div class="row">
    <div class="col mb-3">
        <h1><?= "{$section->name} ({$section->section_id})"; ?></h1>
        <p>
            <?= bookCount($section->section_id); ?> books, <?= copyCount($section->section_id); ?> copies
        </p>
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
        <div class="col mb-3">
            <button type="submit" class="btn btn-success w-100">
                <?= bi('check'); ?> Save Changes
            </button>
        </div>
    </div>

    <?php if (copyCount($section->section_id) === 0): ?>
        <div class="row">
            <div class="col mb-3">
                <a href="<?= site_url("sections/{$section->section_id}/delete"); ?>" class="btn btn-danger w-100" id="btnDelete">
                    <?= bi('delete'); ?> Delete section
                </a>
            </div>
        </div>
    <?php endif; ?>
</form>
<?php
$this->endSection();
$this->section('script');
?>
<script>
    $(function() {
        $('#btnDelete').click(function(e) {
            let confirm = window.confirm('Are you sure you want to delete this section?');

            if (confirm) {
                return true;
            } else {
                e.preventDefault();
                return false;
            }
        })
    })
</script>
<?php
$this->endSection();
?>