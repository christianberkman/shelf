<?php
    $this->extend('layout'); 
    $this->section('body');
?>
<div class="row">
    <div class="col">
        <h1>
            <?=$book->title;?>
        </h1>
    </div>
</div><!--/row-->

<form method="post" action="<?=site_url("/books/{$book->book_id}"); ?>">
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" value="<?=old('title') ?? $book->title; ?>" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="subtitle" class="form-label">Title</label>
                <input type="text" name="title" id="subtitle" value="<?=old('subtitle') ?? $book->subtitle; ?>" class="form-control" />
            </div>

            <div class="mb-3">
                <label for="series" class="form-label">Series</label>
                <div class="input-group">
                    <input type="hidden" id="series_id" name="series_id" value="<?=old('series_id') ?? $book->series_id; ?>" />
                    <input type="text" id="series" value="<?=$book->seriesTitle; ?>" class="form-control" disabled />
                    <button type="button" class="btn btn-primary"><?=bi('find'); ?></button>
                </div>
            </div>

            <div class="mb-3">
                <label for="part" class="form-label">Part</label>
                <input type="text" name="part" id="part" value="<?=old('subtitle') ?? $book->subtitle; ?>" class="form-control" />
            </div>

            <div class="mb-3">
                <label for="series" class="form-label">Authors</label>
                
                <?php foreach($book->authorEntities as $author): ?>
                <div class="mb-3 author">
                    <div class="input-group">
                        <input type="hidden" name="author_ids[]" value="<?=$author->author_id; ?>" />
                        <input type="text" value="<?=$author->name; ?>" class="form-control" disabled />
                        <button type="button" class="btn btn-danger"><?=bi('delete'); ?></button>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <button type="button" class="btn btn-success">
                    <?=bi('add'); ?> Add author
                </button>
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="count" class="form-label">Copies</label>
                    <input type="number" name="count" id="count" min="0" max="1000" value="<?=old('count') ?? $book->count; ?>" class="form-control" />
                </div>

                <div class="col mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" name="price" id="count" min="0" value="<?=old('price') ?? $book->price; ?>" class="form-control" />
                </div>
            </div><!--/row--->
        </div><!--/col-->

        <div class="col-lg-6 mb-3">
            <label for="note" class="form-label">Note</label>
            <textarea id="note" name="note" rows="5" class="form-control w-100"><?=$book->note; ?></textarea>
        </div>
    </div><!--/row-->

    <div class="row">
        <div class="col mb-3">
            <button type="submit" class="btn btn-success w-100">
                <?=bi('check');?> Save changes
            </button>
        </div>
    </div><!--/row-->
</form>

</div>
<?php $this->endSection(); ?>
