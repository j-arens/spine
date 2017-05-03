<?php get_template_part('partials/page-header'); ?>
<?php if (have_posts()): ?>
  <?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('partials/content-archive'); ?>
  <?php endwhile; ?>
  <nav class="pagination-nav">
    <div class="nav-previous alignleft"><?php next_posts_link( 'Old Posts' ); ?></div>
    <div class="nav-next alignright"><?php previous_posts_link( 'Newer Posts' ); ?></div>
  </nav>
<?php endif; ?>
<!-- set max number of posts in admin->reading->blog pages show at most -->
