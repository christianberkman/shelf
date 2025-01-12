<!-- Add series modal -->
<div class="modal fade" id="addSeriesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"><?= bi('add'); ?> Add series to book</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="addSeriesQuery" class="form-control" placeholder="Find series" class="form-control mb-3" />

                <div id="addSeriesResults" class="mt-3 mb-3">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div><!--/modal-content-->
    </div><!--/modal-dialog-->
</div><!--/modal-->


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

        $('#addAuthorModal').on('shown.bs.modal', function(e) {
            findAuthorDiv.html('')
            $('#addAuthorQuery').val('')
            $('#addAuthorQuery').focus()
        })

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
         * Series
         */
        const findSeriesDiv = $('#addSeriesResults');
        let seriesRequest = null;
        let findSeriesTemplate = Handlebars.compile($('#findSeriesTemplate').html())
        let createSeriesTemplate = Handlebars.compile($('#createSeriesTemplate').html())

        $('#removeSeriesButton').click(function(e) {
            $('#series_id').val('')
            $('#series_add').val('')
            $("#series").val('')
        })

        $('#addSeriesModal').on('shown.bs.modal', function(e) {
            findSeriesDiv.html('')
            $('#addSeriesQuery').val('')
            $('#addSeriesQuery').focus()
        })

        $('#addSeriesQuery').on('input', function(e) {
            let query = $(this).val()

            if (seriesRequest) seriesRequest.abort()

            findSeriesDiv.html('<p><em>Finding series...</em></p>');

            seriesRequest = $.getJSON('<?= site_url('series/find/json'); ?>?q=' + query, function(data) {
                switch (data.msg) {
                    case 'query-too-short':
                        findSeriesDiv.html('<p><strong>Query is too short</strong></p>')
                        return;
                        break;
                    case 'no-results':
                        findSeriesDiv.html(createSeriesTemplate(data))
                        return;
                        break;
                }

                findSeriesDiv.html(findSeriesTemplate(data))
            })
        })

        $('body').on('click', '.selectSeriesButton', function(e) {
            $('#series_id').val($(this).attr('data-series-id'))
            $('#series').val($(this).attr('data-series-title'))
        })

        $('body').on('click', '#createSeriesButton', function(e) {
            console.log('click')
            $('#series_id').val('')
            let addSeries = $(this).attr('data-series-name')
            console.log(addSeries)
            $('#series_add').val(addSeries)
            $('#series').val(addSeries)
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
<script id="findSeriesTemplate" type="text/x-handlebars-template">
    <table class="table table-striped table-sm">
        <tbody>
        {{#each results}}
            <tr>
                <td>{{series_title}}</td>
                <td>
                    <button type="button" class="selectSeriesButton btn btn-success btn-sm" data-series-id="{{series_id}}" data-series-title="{{series_title}}" data-bs-dismiss="modal">
                        <?= bi('check'); ?> Select
                    </button>
                </td>
            </tr>
        {{/each}}
        </tbody>
    </table>

    {{#unless exactMatch}}
    <p>
        <button type="button" id="createSeriesButton" data-series-name="{{sortableQuery}}" class="btn btn-sm btn-success" data-bs-dismiss="modal">
            <?= bi('add'); ?> Create new series "{{sortableQuery}}"
        </button>
    </p>
    {{/unless}}
</script>
<script id="createSeriesTemplate" type="text/x-handlebars-template">
    <p>
        <em>No results</em>
    </p>

    {{#unless exactMatch}}
    <p>
        <button type="button" id="createSeriesButton" data-series-name="{{sortableQuery}}" class="btn btn-sm btn-success" data-bs-dismiss="modal">
            <?= bi('add'); ?> Create new series "{{sortableQuery}}"
        </button>
    </p>
    {{/unless}}
</script>