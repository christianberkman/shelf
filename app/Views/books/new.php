<?php
$this->extend('layout');
$this->section('body');
?>
<?= match (session('alert')) {
    'success'       => alert('Success', 'Added new book', 'success'),
    'error'         => alert('Error', 'Could not add new book', 'danger'),
    'no-authors'    => alert('Error', 'A book must have least one author', 'warning'),
    'error-authors' => alert('Error', 'Error in author information, please check and try again', 'warning'),
    default         => null,
};
?>
<div class="row">
    <div class="col">
        <h1>
            <?= bi('add'); ?> Add new book
        </h1>
    </div>
</div><!--/row-->

<form method="post" action="<?= current_url(); ?>" id="bookForm">
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" value="<?= old('title') ?? $book->title; ?>" class="form-control <?=hasValidationError('title') ? 'is-invalid' : ''; ?>" required />
                <?= validationMessage('series_title'); ?>
            </div>

            <div class="mb-3">
                <label for="subtitle" class="form-label">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" value="<?= old('subtitle') ?? $book->subtitle; ?>" class="form-control" />
            </div>

            <div class="mb-3">
                <label for="series" class="form-label">Series</label>
                <div class="input-group">
                    <input type="hidden" id="series_id" name="series_id" value="<?= old('series_id') ?? $book->series_id; ?>" />
                    <input type="text" id="series" value="<?= $book->seriesTitle; ?>" class="form-control" disabled />
                    <button type="button" class="btn btn-primary"><?= bi('find'); ?></button>
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

<!-- Add author modal -->
<div class="modal fade" id="addAuthorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"><?= bi('add'); ?> Add author to book</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="addAuthorQuery" class="form-control" placeholder="Find author" class="form-control mb-3" />

                <div id="addAuthorResults" class="mt-3 mb-3">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div><!--/modal-content-->
    </div><!--/modal-dialog-->
</div><!--/modal-->

<?php
$this->endSection();
$this->section('script');
?>
<script>
    $(function() {
        /**
         * Author
         */
        const findAuthorDiv = $('#addAuthorResults');
        let authorRequest = null;
        const findAuthorSource = $('#findAuthorTemplate').html()
        let findAuthorTemplate = Handlebars.compile(findAuthorSource)
        let createAuthorTemplate = Handlebars.compile($('#createAuthorTemplate').html())
        let addAuthorTemplate = Handlebars.compile($('#addAuthorTemplate').html())
        let addCreatedAuthorTemplate = Handlebars.compile($('#addCreatedAuthorTemplate').html())
        const authorsDiv = $('#authors')

        $('#addAuthorQuery').on('input', function(e) {
            let query = $(this).val()

            if (authorRequest) authorRequest.abort()

            findAuthorDiv.html('<p><em>Finding authors...</em></p>');

            authorRequest = $.getJSON('<?= site_url('authors/find/json'); ?>?q=' + query, function(data) {
                switch (data.msg) {
                    case 'query-too-short':
                        findAuthorDiv.html('<p><strong>Query is too short</strong></p>')
                        return;
                        break;
                    case 'no-results':
                        findAuthorDiv.html(createAuthorTemplate(data))
                        return;
                        break;
                }

                findAuthorDiv.html(findAuthorTemplate(data))
            })
        })

        $('body').on('click', '.addAuthorBtn', function(e) {
            const data = {
                author_id: $(this).attr('data-author-id'),
                name: $(this).attr('data-author-name'),
            }

            authorsDiv.append(addAuthorTemplate(data))

            findAuthorDiv.html('')
            $('#addAuthorQuery').val('')
        })

        $('body').on('click', '.deleteAuthorBtn', function(e) {
            const authorDiv = $(this).closest('.author')
            authorDiv.remove()
        })

        $('body').on('click', '#createAuthorButton', function(e) {
            const authorName = $(this).attr('data-author-name')

            data = {
                name: authorName
            }
            authorsDiv.append(addCreatedAuthorTemplate(data))

            findAuthorDiv.html('')
            $('#addAuthorQuery').val('')

        })

        /**
         * Submit
         */
        $('#bookForm').submit(function(e) {
            // Must have at least one author
            const authorCount = $(".author").length
            if (authorCount < 1) {
                e.preventDefault()
                alert('You must add at least one author')
                return false
            }
        })
    })
</script>

<script id="findAuthorTemplate" type="text/x-handlebars-template">
    <table class="table table-striped table-sm">
        <tbody>
        {{#each results}}
            <tr>
                <td>{{name}}</td>
                <td>
                    <button type="button" class="addAuthorBtn btn btn-success btn-sm" data-author-id="{{author_id}}" data-author-name="{{name}}" data-bs-dismiss="modal">
                        <?= bi('add'); ?> Add
                    </button>
                </td>
            </tr>
        {{/each}}
        </tbody>
    </table>

    {{#unless exactMatch}}
    <p>
        <button type="button" id="createAuthorButton" data-author-name="{{sortableQuery}}" class="btn btn-sm btn-success" data-bs-dismiss="modal">
            <?= bi('add'); ?> Create new author "{{sortableQuery}}"
        </button>
    </p>
    {{/unless}}
</script>
<script id="createAuthorTemplate" type="text/x-handlebars-template">
    <p>
        <em>No results</em>
    </p>

    {{#unless exactMatch}}
    <p>
        <button type="button" id="createAuthorButton" data-author-name="{{sortableQuery}}" class="btn btn-sm btn-success" data-bs-dismiss="modal">
            <?= bi('add'); ?> Create new author "{{sortableQuery}}"
        </button>
    </p>
    {{/unless}}
</script>
<script id="addAuthorTemplate" type="text/x-handlebars-template">
    <div class="mb-3 author">
        <div class="input-group">
            <input type="hidden" name="author_ids[]" value="{{author_id}}" />
            <input type="text" value="{{name}}" class="form-control" disabled />
            <button type="button" class="btn btn-danger deleteAuthorBtn">
                <?= bi('delete'); ?>
            </button>
        </div>
    </div>
</script>
<script id="addCreatedAuthorTemplate" type="text/x-handlebars-template">
    <div class="mb-3 author">
        <div class="input-group">
            <input type="hidden" name="create_authors[]" value="{{name}}" />
            <input type="text" value="{{name}}" class="form-control" disabled />
            <button type="button" class="btn btn-danger deleteAuthorBtn">
                <?= bi('delete'); ?>
            </button>
        </div>
    </div>
</script>
<?php
$this->endSection();
?>