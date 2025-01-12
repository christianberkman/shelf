<?php
$this->extend('layout');
$this->section('body');
?>
<?= match (session('alert')) {
    'success'        => alert('Success', 'Saved book information', 'success'),
    'insert-success' => alert('Success', 'Added new book', 'success'),
    'error'          => alert('Error', 'Could not save book information', 'danger'),
    'no-authors'     => alert('Error', 'A book must have least one author', 'warning'),
    'error-authors'  => alert('Error', 'Error in author information, please check and try again', 'warning'),
    'error-series'   => alert('Error', 'Could not save book series', 'danger'),
    default          => null,
};
?>
<div class="row">
    <div class="col">
        <h1>
            <?= $book->title; ?>
        </h1>
    </div>
</div><!--/row-->

<form method="post" action="<?= site_url("/books/{$book->book_id}"); ?>" id="bookForm">
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" value="<?= old('title') ?? $book->title; ?>" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="subtitle" class="form-label">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" value="<?= old('subtitle') ?? $book->subtitle; ?>" class="form-control" />
            </div>

            <div class="mb-3">
                <label for="series" class="form-label">Series</label>
                <div class="input-group">
                    <input type="hidden" id="series_id" name="series_id" value="<?= old('series_id') ?? $book->series_id; ?>" />
                    <input type="hidden" id="series_add" name="series_add" value="<?= old('series_add'); ?>" />
                    <input type="text" id="series" value="<?= $book->seriesTitle; ?>" class="form-control" disabled />
                    <button type="button" id="removeSeriesButton" class="btn btn-danger"><?= bi('delete'); ?></button>
                    <button type="button" id="findSeriesButton" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSeriesModal"><?= bi('find'); ?></button>
                </div>
            </div>

            <div class="mb-3">
                <label for="part" class="form-label">Part</label>
                <input type="text" name="part" id="part" value="<?= old('part') ?? $book->part; ?>" class="form-control" />
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="count" class="form-label">Copies</label>
                    <input type="number" name="count" id="count" min="0" max="1000" value="<?= old('count') ?? $book->count ?? 0; ?>" class="form-control" />
                </div>

                <div class="col mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" name="price" id="count" min="0" value="<?= old('price') ?? $book->price; ?>" class="form-control" />
                </div>
            </div><!--/row--->

            <div class="mb-3">
                <label for="note" class="form-label">Note</label>
                <input type="text" id="note" name="note" value="<?= old('note') ?? $book->note; ?>" class="form-control" />
            </div>

        </div><!--/col-->

        <div class="col-lg-6 mb-3">

            <div class="mb-3">
                <label for="sectionId" class="form-label">
                    Section
                </label>
                <?= view('sections/dropdown', ['selected' => old('section_id') ?? $book->section_id]); ?>
            </div>

            <div class="mb-3">
                <label for="series" class="form-label">Authors</label>

                <div id="authors">
                    <?php foreach ($book->authorEntities as $author): ?>
                        <div class="mb-3 author">
                            <div class="input-group">
                                <input type="hidden" name="author_ids[]" value="<?= $author->author_id; ?>" />
                                <input type="text" value="<?= $author->name; ?>" class="form-control" disabled />
                                <button type="button" class="btn btn-danger deleteAuthorBtn"><?= bi('delete'); ?></button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAuthorModal">
                    <?= bi('add'); ?> Add author
                </button>
            </div>
        </div><!--/row-->

        <div class="row">
            <div class="col mb-3">
                <button type="submit" class="btn btn-success w-100">
                    <?= bi('check'); ?> Save changes
                </button>
            </div>
        </div><!--/row-->
</form>
</div>
<?php
$this->endSection();

$this->section('script');
echo view('books/view_scripts');
$this->endSection();
?>