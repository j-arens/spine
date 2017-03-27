<article <?php post_class(); ?>>
  <div class="entry-content">
    <?php if (has_post_thumbnail()): ?>
      <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="post-thumbnail" class="post-thumbnail" />
    <?php endif; ?>
    <?php the_content(); ?>
  </div>
  <footer>
    <nav class="pagination-nav">
      <?php
        previous_post_link( '<a class="prev-link" %link', '< Previous Post' );
        next_post_link( '<a class="next-link" %link', 'Next Post >' );
      ?>
    </nav>
  </footer>
</article>
