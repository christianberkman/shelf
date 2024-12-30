<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shelf</title>
    <link href="/assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="/assets/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar bg-primary bg-gradient">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="/">
                <?= bi('bookshelf'); ?> Shelf
            </a>
        </div><!--/container-fluid-->
    </nav>

    <div class="container mt-3 mb-3" id="body-container">
      <?= view('breadcrumbs', ['crumbs' => $crumbs ?? [], 'current' => $current ?? null]); ?>
      <?php $this->renderSection('body'); ?>
    </div>

    <script src="/assets/bootstrap/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="/assets/jquery/jquery-3.7.1.min.js"></script>
    <script src="/assets/handlebars/handlebars.min.js"></script>
    <?php $this->renderSection('script'); ?>
  </body>
</html>