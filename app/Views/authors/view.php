<?php
$this->extend('layout');
$this->section('body');
?>
<?= match (session('alert')) {
    'success'      => alert('Success', 'Saved author details', 'success'),
    'error'        => alert('Error', 'Could not save author details', 'danger'),
    'delete-error' => alert('Error', 'Could not delete author', 'danger'),
    default        => null,
};
?>

<div class="row">
    <div class="col">
        <h1><?=$author->name; ?></h1>

        <form method="post" action="<?=current_url(); ?>">

            <div class="row">
                <div class="col mb-3">
                    <div class="mb-3">
                        <label for="authorId" class="form-label">
                            Author ID
                        </label>
                        <input type="text" id="authorId" name="authorId" value="<?=$author->author_id; ?>"
                            class="form-control" disarbled />
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Name
                        </label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="<?=old('name') ?? $author->name; ?>" required />
                    </div>

                </div><!--/col-->

                <div class="col mb-3">
                    <h2>Books</h2>
                    <p>
                        Book count: <strong><?=$author->bookCount; ?></strong>
                    </p>

                    <div style="max-height: 200px; overflow-y: scroll;">
                        <table class="table table-striped table-sm">
                            <tbody>
                                <?php foreach($books as $book): ?>
                                <tr>
                                    <td>
                                        <a href="/books/<?=$book->book_id; ?>"><?=$book->title; ?></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div><!--/scroll-->
                </div>
            </div><!--/row-->

            <button type="submit" class="btn btn-success w-100 mb-3">
                <?=bi('check'); ?> Save changes
            </button>
        </form>

        <?php if($author->bookCount >= 0): ?>
        <a href="<?=site_url("authors/{$author->author_id}/delete"); ?>" class="btn btn-danger w-100 mb-3" id="btnDelete">
            <?=bi('delete'); ?> Delete author
        </a>
        <?php endif; ?>

    </div><!--/col-->
</div><!--/row-->
<?php
$this->endSection();
$this->section('script');
?>
<script>
    $(function(){
        $('#btnDelete').click(function(e){
            let confirm = window.confirm('Are you sure you want to delete this author?');

            if(confirm){
                return true;
            } else{
                e.preventDefault();
                return false;
            }
        })
    })
</script>
<?php
$this->endSection();
?>