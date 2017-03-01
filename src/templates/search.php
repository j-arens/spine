<?php get_template_part('partials/page-header'); ?>

<?php if (!have_posts()) : ?>
  <div class="no-posts">
    <p>Sorry, we couldn't find anything. Try another search?</p>
    <?php get_search_form(); ?>
  </div>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('partials/content-search'); ?>
<?php endwhile; ?>

<?php the_posts_navigation(); ?>
