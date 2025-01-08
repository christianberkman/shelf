<?php
$this->extend('layout');
$this->section('body');
?>
<div class="row row-cols-3">
    <div class="col mb-3">
        <div class="card bg-light">
            <div class="card-body">
                <h2 class="card-heading text-center mb-3">
                    <?= bi('book'); ?> Books
                </h2>

                <p class="text-center"><?= bookCount(); ?> titles, <?= copyCount(); ?> copies</p>

                <a href="<?= site_url('books/find'); ?>" class="btn btn-outline-primary btn-lg w-100">
                    <?= bi('search'); ?> Find a book
                </a>

                <a href="<?= site_url('books/new'); ?>" class="btn btn-outline-success btn-lg w-100 mt-3">
                    <?= bi('add'); ?> Add a book
                </a>

                <a href="<?= site_url('books/browse'); ?>" class="btn btn-outline-secondary btn-lg w-100 mt-3">
                    <?= bi('list'); ?> Browse books
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col mb-3-->

    <div class="col mb-3">
        <div class="card bg-light">
            <div class="card-body">
                <h2 class="card-heading text-center mb-3">
                    <?= bi('link'); ?> Series
                </h2>

                <p class="text-center"><?= seriesCount(); ?> series</p>

                <a href="<?= site_url('series/find'); ?>" class="btn btn-outline-primary btn-lg w-100">
                    <?= bi('search'); ?> Find a series
                </a>

                <a href="<?= site_url('series/new'); ?>" class="btn btn-outline-success btn-lg w-100 mt-3">
                    <?= bi('add'); ?> Add a series
                </a>

                <a href="<?= site_url('series/find/all'); ?>" class="btn btn-outline-secondary btn-lg w-100 mt-3">
                    <?= bi('list'); ?> Browse series
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col mb-3-->

    <div class="col mb-3">
        <div class="card bg-light">
            <div class="card-body">
                <h2 class="card-heading mb-3 text-center">
                    <?= bi('person'); ?> Authors
                </h2>

                <p class="text-center"><?= authorCount(); ?> authors</p>

                <a href="<?= site_url('authors/find'); ?>" class="btn btn-outline-primary btn-lg w-100">
                    <?= bi('search'); ?> Find an author
                </a>

                <a href="<?= site_url('authors/new'); ?>" class="btn btn-outline-success btn-lg w-100 mt-3">
                    <?= bi('add'); ?> Add an author
                </a>

                <a href="<?= site_url('/authors/browse'); ?>" class="btn btn-outline-secondary btn-lg w-100 mt-3">
                    <?= bi('list'); ?> Browse authors
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col mb-3-->

    <div class="col mb-3 col mb-3">
        <div class="card bg-light">
            <div class="card-body">
                <h2 class="card-heading mb-3 text-center">
                    <?= bi('collection'); ?> Sections
                </h2>

                <p class="text-center"><?= sectionCount(); ?> sections</p>

                <a href="<?= site_url('sections'); ?>" class="btn btn-outline-primary btn-lg w-100">
                    <?= bi('view'); ?> View sections
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col mb-3-->

</div><!--/row-->
<?php
$this->endSection();
?>