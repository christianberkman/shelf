<?php
$this->extend('layout');
$this->section('body');
?>
<?= match (session('alert')) {
    'delete-success' => alert('Success', 'Sucesfully deleted an serie', 'success'),
    default          => null,
};
?>
<div class="row">
    <div class="col-lg-10 m-auto">
        <form method="get" action="<?= site_url('find/series/all'); ?>">
            <h1>Find a series</h1>
            <input type="text" class="form-control border-info border-4" name="q" id="findSerieInput" placeholder="Begin typing..." />
            <div class="row">
                <div id="serieResults" class="col mt-3">
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
        const serieResults = $('#serieResults');
        let source = $('#serieTemplate').html()
        const template = Handlebars.compile(source)
        const noResultTemplate = Handlebars.compile($('#noResultTemplate').html())
        let currentRequest = null

        $('#findSerieInput').on('input', function(){
            // Cancel current request
            if(currentRequest) {
                currentRequest.abort();
            }

            // Remove previous results
            serieResults.html('<em>Finding series...</em>')

            let query = $(this).val()
            currentRequest = $.getJSON('/find/series/ajax?max=10&q='+query, function(data){
                // No results or query too short
                switch(data.msg){
                    case 'query-too-short':
                        serieResults.html('<p><strong>Query is too short</strong></p>')
                        return;
                    case 'no-results':
                        serieResults.html(noResultTemplate(data));
                        return;
                    break;
                }

                // Results!
                serieResults.html(template(data));
            });
        })
    })
</script>

<script id="noResultTemplate" type="text/x-handlebars-template">
    <p>
        <em>No Results found</em>
    </p>

    <a href="/series/new?series_title={{sortableQuery}}" class="btn btn-success">
        <?= bi('add'); ?> Add new series "{{sortableQuery}}"
    </a>
</script>


<script id="serieTemplate" type="text/x-handlebars-template">
    <p>
        Showing <strong>{{shown}} results for "{{query}}"</strong>
    </p>
    <table class="table table-striped table-hover w-100">
        <tbody>
    {{#each results}}
            <tr style="position: relative">
                <td>
                    <strong><a href="/series/{{series_id}}" class="stretched-link link-underline link-underline-opacity-0">{{series_title}}</a></strong> &mdash; {{count}} books
                </td>
            </tr>
    {{/each}}
        </tbody>
    </table>

    {{#if more}}
        <button type="submit" class="btn btn-primary">Show all {{count}} results for "{{query}}"</a>
    {{/if}}
</script>

<?php $this->endSection(); ?>