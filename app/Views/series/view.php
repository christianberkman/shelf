<?php
$this->extend('layout');
$this->section('body');
?>
<?= match (session('alert')) {
    'added'        => alert('Success', 'New series added', 'success'),
    'success'      => alert('Success', 'Saved series details', 'success'),
    'error'        => alert('Error', 'Could not save series details', 'danger', session('errors')),
    'delete-error' => alert('Error', 'Could not delete series', 'danger'),
    'duplicate'    => alert('Duplicate', 'Series already exists', 'warning'),
    default        => null,
};
?>

<div class="row">
    <div class="col">
        <h1><?= $series->series_title; ?></h1>

        <form method="post" action="<?= current_url(); ?>">

            <div class="row">
                <div class="col mb-3">
                    <div class="mb-3">
                        <label for="serieId" class="form-label">
                            Series ID
                        </label>
                        <input type="text" id="serieIds" name="seriesId" value="<?= $series->serie_id; ?>"
                            class="form-control" disabled />
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Series Title
                        </label>
                        <input type="text" id="series_title" name="series_title" class="form-control <?= hasValidationError('series_title') ? 'is-invalid' : ''; ?>"
                            value="<?= old('series_title') ?? $series->series_title; ?>" required />
                        <div class="invalid-feedback">
                            <?=validationMessage('series_title'); ?>
                        </div>
                    </div>

                </div><!--/col-->

                <div class="col mb-3">
                    <h2>Books</h2>
                    <p>
                        Book count: <strong><?= $series->bookCount; ?></strong>
                    </p>

                    <div style="max-height: 200px; overflow-y: scroll;">
                        <table class="table table-striped table-sm">
                            <tbody>
                                <?php foreach ($books as $book): ?>
                                    <tr>
                                        <td>
                                            <a href="/book/<?= $book->book_id; ?>">
                                                <?= $book->title; ?>
                                                <?=(! empty($book->part) ? "Part {$book->part}" : ''); ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div><!--/scroll-->
                </div>
            </div><!--/row-->

            <button type="submit" class="btn btn-success w-100 mb-3">
                <?= bi('check'); ?> Save changes
            </button>
        </form>

        <?php if ($series->bookCount >= 0): ?>
            <a href="<?= site_url("series/{$series->series_id}/delete"); ?>" class="btn btn-danger w-100 mb-3" id="btnDelete">
                <?= bi('delete'); ?> Delete series
            </a>
        <?php endif; ?>

    </div><!--/col-->
</div><!--/row-->
<?php
$this->endSection();
$this->section('script');
?>
<script>
    $(function() {
        $('#btnDelete').click(function(e) {
            let confirm = window.confirm('Are you sure you want to delete this series?');

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