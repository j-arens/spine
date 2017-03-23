<article <?php post_class(); ?>>
  <header>
    <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
  </header>
  <div class="entry-excerpt">
      <?php if (has_post_thumbnail()): ?>
        <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="post-thumbnail" class="post-thumbnail">
      <?php endif; ?>
    <?php the_excerpt(); ?>
  </div>
</article>
