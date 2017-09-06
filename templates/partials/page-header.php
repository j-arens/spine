<div class="page-header">
  <?php if ( is_search() ): ?>
    <h1 class="page-header__title"><?php echo 'Search: ' . get_search_query(); ?></h1>
  <?php elseif ( is_404() ): ?>
    <h1 class="page-header__title">Not Found</h1>
  <?php elseif ( is_archive() ): ?>
    <h1 class="page-header__title"><?php echo ucfirst(get_post_type()); ?> Archive</h1>
  <?php else: ?>
    <h1 class="page-header__title"><?php echo get_the_title(); ?></h1>
  <?php endif ?>
</div>
