<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?=site_urL('/'); ?>">Home</a></li>
    <?php
     if(isset($crumbs)):
         foreach($crumbs as $crumb):
             ?>
    <li class="breadcrumb-item"><a href="<?=site_url($crumb[1] ?? '#'); ?>"><?=htmlspecialchars($crumb[0]); ?></a></li>
    <?php
         endforeach;
     endif;
    ?>
    <?php if(isset($current)): ?>
    <li class="breadcrumb-item active"><?=htmlspecialchars($current); ?></li>
    <?php endif; ?>
  </ol>
</nav>