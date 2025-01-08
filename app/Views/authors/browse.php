<?php
$this->extend('layout');
$this->section('body');
?>
<div class="row">
    <div class="col">
        <h1>
            <?=bi('list'); ?> Browse Authors
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
                        <label for="sortBy" class="form-label">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="title" <?=($sort === 'title' ? 'selected' : ''); ?>>Series Title (A-Z)</option>
                            <option value="count_desc" <?=($sort === 'count_desc' ? 'selected' : ''); ?>>Book Count (high to low)</option>
                            <option value="count_asc" <?=($sort === 'count_asc' ? 'selected' : ''); ?>>Book Count (low to high)</option>
                        </select>
                    </div>
                </div>
            </form>

        <p>
            Showing <?=count($authors); ?> of <?=$pager->getTotal(); ?> results
        </p>

        <?=$pager->links(); ?>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Book Count</th>
                </tr>
                <tbody>
                    <?php foreach($authors as $author): ?>
                    <tr class="position-relative">
                        <td>
                            <a href="<?=site_url("/authors/{$author->author_id}"); ?>" class="stretched-link">
                                <?=$author->name; ?>
                            </a>
                        </td>
                        <td><?=$author->bookCount; ?></td>
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