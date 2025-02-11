<?php
$this->extend('layout');
$this->section('body');
echo match (session('alert')) {
    'delete-success' => alert('Success', 'Book deleted', 'success'),
    default          => null,
};
?>

<div class="row">
    <div class="col-lg-10 m-auto">
        <form method="get" action="<?= site_url('books/browse'); ?>">
            <h1>Find a book</h1>
            <input type="text" class="form-control border-info border-4" name="query" id="findBookInput" placeholder="Begin typing..." />
            <div class="row">
                <div id="bookResults" class="col mt-3">
                </div>
        </form>
    </div><!--/col-->
</div><!--/row-->
<?php
$this->endSection();
$this->section('script');
?>
<script>
    $(function() {
                const bookResults = $('#bookResults');
                let source = $('#bookTemplate').html()
                let template = Handlebars.compile(source)
                let currentRequest = null

                $('#findBookInput').on('input', function() {
                        // Cancel current request
                        if (currentRequest) {
                            currentRequest.abort();
                        }

                        // Remove previous results
                        bookResults.html('<em>Finding books...</em>')

                        let query = $(this).val()
                        currentRequest = $.getJSON('/books/find/json?max=10&q=' + query, function(data) {
                                // No results or query too short
                                switch (data.msg) {
                                    case 'query-too-short':
                                        bookResults.html('<p><strong>Query is too short</strong></p>')
                                        return;
                                        break;
                                    case 'no-results':
                                        bookResults.html('<p><strong>No results</strong></p><p><a href="<?= site_url('/books/new/'); ?>?title='+data.sortableQuery+'" class="btn btn-success"><?= bi('plus'); ?> Add book with title "'+data.sortableQuery+'"</a>');
                                            return;
                                            break;
                                        }

                                        // Results!
                                        bookResults.html(template(data));
                                });
                        })
                })
</script>

<script id="bookTemplate" type="text/x-handlebars-template">
    <p>
        Showing <strong>{{shown}} results for "{{q}}"</strong>
    </p>
    <table class="table table-striped table-hover w-100">
        <tbody>
    {{#each results}}
            <tr style="position: relative">
                <td>{{section_id}}</td>
                <td>
                <strong><a href="/books/{{book_id}}" class="stretched-link link-underline link-underline-opacity-0">{{title}}</a></strong>{{#if subtitle}} <em>{{subtitle}}</em>{{/if}}
                &mdash; {{authors}}<br />
                {{series}} {{#if part}}Part {{part}}{{/if}}
                </td>
            </tr>
    {{/each}}
        </tbody>
    </table>

    <div class="row">
        <div class="col-auto me-auto">
            <a href="<?= site_url('/books/new/'); ?>?title={{sortableQuery}}" class="btn btn-success">
                <?= bi('plus'); ?> Add book with title "{{sortableQuery}}"
            </a>
        </div>

        <div class="col-auto ms-auto">
            {{#if more}}
                <button type="submit" class="btn btn-primary">Show all {{count}} results for "{{q}}"</button>
            {{/if}}
        </div>
    </div><!--/row-->

</script>

<?php $this->endSection(); ?>