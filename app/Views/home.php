<?php
$this->extend('layout');
$this->section('body');
?>
<div class="row row-cols-3">
    <div class="col mb-3">
        <div class="card bg-light">
            <div class="card-body">
                <h2 class="card-heading text-center mb-3">
                    <i class="bi bi-book"></i> Books
                </h2>

                <p class="text-center"><?= bookCount(); ?> titles, <?= copyCount(); ?> copies</p>

                <a href="/books/find" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-search"></i> Find a book
                </a>
                <a href="/books/new" class="btn btn-success btn-lg w-100 mt-3">
                    <i class="bi bi-plus"></i> Add a book
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col mb-3-->

    <div class="col mb-3">
        <div class="card bg-light">
            <div class="card-body">
                <h2 class="card-heading text-center mb-3">
                    <i class="bi bi-link"></i> Series
                </h2>

                <p class="text-center"><?= seriesCount(); ?> series</p>

                <a href="/series/find" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-search"></i> Find a series
                </a>
                <a href="/series/new" class="btn btn-success btn-lg w-100 mt-3">
                    <i class="bi bi-plus"></i> Add a series
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col mb-3-->

    <div class="col mb-3">
        <div class="card bg-light">
            <div class="card-body">
                <h2 class="card-heading mb-3 text-center">
                    <i class="bi bi-person"></i> Authors
                </h2>

                <p class="text-center"><?= authorCount(); ?> authors</p>

                <a href="/authors/find" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-search"></i> Find an author
                </a>
                <a href="/authors/new" class="btn btn-success btn-lg w-100 mt-3">
                    <i class="bi bi-plus"></i> Add an author
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col mb-3-->

    <div class="col mb-3 col mb-3">
        <div class="card bg-light">
            <div class="card-body">
                <h2 class="card-heading mb-3 text-center">
                    <i class="bi bi-col mb-3lection"></i> Sections
                </h2>

                <p class="text-center"><?= sectionCount(); ?> sections</p>

                <a href="/sections/" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-gear"></i> Manage Sections
                </a>
            </div>
        </div><!--/card-->
    </div><!--/col mb-3-->

</div><!--/row-->
<?php
    $this->endSection();
?>