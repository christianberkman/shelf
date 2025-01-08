<?php
$this->extend('layout');
$this->section('body');
?>
<div class="row">
    <div class="col">
        <h1>
            <?=bi('list'); ?> Browse Books
        </h1>

        <form method="get" action="<?=current_url(); ?>" id="optionForm">
            <div class="row justify-content-end">
                <div class="col-lg-3 mb-3">
                    <input type="hidden" name="q" value="<?=$query; ?>" />
                    <?php if(! empty($query)): ?>
                    <label class="form-label">
                        Query
                    </label>
                        <p><?=$query; ?></p>
                    </p>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4 mb-3">
                    <label for="section" class="form-label">
                        Section
                    </label>
                    <?=view('sections/dropdown.php', ['selected' => $sectionId]); ?>
                </div>

                <div class="col-lg-4 mb-3">
                        <label for="sortBy" class="form-label">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="title" <?=($sort === 'title' ? 'selected' : ''); ?>>Series Title (A-Z)</option>
                            <option value="section" <?=($sort === 'section' ? 'selected' : ''); ?>>Section ID (A-Z)</option>
                            <option value="count_desc" <?=($sort === 'count_desc' ? 'selected' : ''); ?>>Book Count (high to low)</option>
                            <option value="count_asc" <?=($sort === 'count_asc' ? 'selected' : ''); ?>>Book Count (low to high)</option>
                        </select>
                    </div>
                </div>
            </form>

        <p>
            Showing <?=count($books); ?> of <?=$pager->getTotal(); ?> results
        </p>

        <?=$pager->links(); ?>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Section</th>
                    <th>Title</th>
                    <th>Series</th>
                    <th>Authors</th>
                    <th>Count</th>
                </tr>
                <tbody>
                    <?php foreach($books as $book): ?>
                    <tr class="position-relative">
                        <td><?=$book->section_id; ?></td>
                        <td>
                            <a href="<?=site_url("/book/{$book->book_id}"); ?>" class="stretched-link">
                                <?=$book->title; ?>
                                <?=(! empty($book->subtitle) ? "&mdash; <em>{$book->subtitle}</em>" : ''); ?>
                            </a>
                        </td>
                        <td>
                            <?=$book->seriesTitle; ?>
                            <?=(! empty($book->part) ? "Part {$book->part}" : ''); ?>
                        </td>
                        <td><?=$book->authors; ?></td>
                        <td><?=$book->count; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </thead>
        </table>

        <?=$pager->links(); ?>
    </div><!--/col-->
</div><!--/row-->
<?php
 $this->endSection();
$this->section('script');
?>
<script>
    $(function(){
        $('#optionForm').change(function(){
            $('#optionForm').submit()
        })
    })
</script>
<?php $this->endSection(); ?>