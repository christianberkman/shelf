<?php
$this->extend('layout');
$this->section('body');
?>

<div class="row">
    <div class="col-lg-10 m-auto">
        <form method="get" action="/authors/find">
            <h1>Find an author</h1>
            <input type="text" class="form-control border-info border-4" name="query" id="findAuthorInput" placeholder="Begin typing..." />
            <div class="row">
                <div id="authorResults" class="col mt-3">
            </div>
        </form>
    </div><!--/col-->
</div><!--/row-->
<?php
$this->endSection();
$this->section('script');
?>
<script>
    $(function(){
        const authorResults = $('#authorResults');
        let source = $('#authorTemplate').html()
        let template = Handlebars.compile(source)
        let currentRequest = null

        $('#findAuthorInput').on('input', function(){
            // Cancel current request
            if(currentRequest) {
                currentRequest.abort();
            }

            // Remove previous results
            authorResults.html('<em>Finding authors...</em>')

            let query = $(this).val()
            currentRequest = $.getJSON('/authors/ajax?max=10&q='+query, function(data){
                // No results or query too short
                switch(data.msg){
                    case 'query-too-short':
                        authorResults.html('<p><strong>Query is too short</strong></p>')
                        return;
                    case 'no-results':
                        authorResults.html('<p><strong>No results</strong></p>');
                        return;
                    break;
                }

                // Results!
                authorResults.html(template(data));
            });
        })
    })
</script>

<script id="authorTemplate" type="text/x-handlebars-template">
    <p>
        Showing <strong>{{shown}} results for "{{query}}"</strong>
    </p>
    <table class="table table-striped table-hover w-100">
        <tbody>
    {{#each results}}
            <tr style="position: relative">
                <td>
                    <strong><a href="/authors/{{author_id}}" class="stretched-link link-underline link-underline-opacity-0">{{name}}</a></strong> &mdash; {{count}} books
                </td>
            </tr>
    {{/each}}
        </tbody>
    </table>

    {{#if more}}
        <a href="/authors/find/results?q={{query}}" class="btn btn-primary">Show all {{count}} results for "{{query}}"</a>
    {{/if}}
</script>

<?php $this->endSection(); ?>