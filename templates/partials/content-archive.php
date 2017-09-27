<article <?php post_class(); ?>>
  <header>
    <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
  </header>
  <div class="entry-excerpt">
    <?php the_excerpt(); ?>
  </div>
</article>
