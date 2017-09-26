<!doctype html>
<html <?php language_attributes(); ?>>
  <?php get_template_part('partials/head'); ?>
  <body <?php body_class(); ?>>
    
    <noscript>
        <p class="browserupgrade">
            You need to have JavaScript enabled to properly view this site.
        </p>
    </noscript>

    <!-- warn IE users to upgrade to a better browser -->
    <script>
        if (!(window.ActiveXObject) && 'ActiveXObject' in window) {
            document.body.insertAdjacentHTML(
                'afterbegin', 
                '<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://outdatedbrowser.com/">upgrade your browser</a> to view this site properly.<p>'
            );
        }
    </script>

    <?php
      do_action('get_header');
      get_template_part('partials/header');
    ?>
    <div class="wrap" role="document">
      <main class="main">
        <?php load_template(Spine\scripts\php\template()->main()); ?>
      </main>
      <?php if (Spine\scripts\php\displaySidebar()) : ?>
        <aside class="sidebar">
          <?php get_template_part('partials/sidebar'); ?>
        </aside>
      <?php endif; ?>
    </div>
    <?php
      do_action('get_footer');
      get_template_part('partials/footer');
      wp_footer();
      get_template_part('partials/search-modal');
    ?>
  </body>
</html>
