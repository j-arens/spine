<div class="page-header">
  <?php if ( is_search() ): ?>
    <h1><?php echo 'Search: ' . get_search_query(); ?></h1>
  <?php else: ?>
    <h1><?php echo get_the_title(); ?></h1>
  <?php endif ?>
</div>
