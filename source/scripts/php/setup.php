<?php

namespace Spine\scripts\php;

use Spine\lib\Template;
use Spine\scripts\php\assetPath;

/**
* Change admin footer message
*/
add_filter('admin_footer_text', function() {
    echo 'Designed and developed by <a href="//diocesan.com" target="_blank">Diocesan Publications</a>. Powered by <a href="//wordpress.org">WordPress<a/>.';
});

/**
 * Theme assets
 */
 add_action('wp_enqueue_scripts', function () {

    //  theme css
     wp_enqueue_style(
         'spine-css', 
         get_stylesheet_uri(), 
         null, 
         filemtime(get_template_directory() . '/style.css'),
         'all'
    );

    //  override css
     wp_enqueue_style(
        'spine-override-css', 
        get_template_directory_uri() . '/override.css', 
        ['spine-style'], 
        filemtime(get_template_directory() . '/override.css'),
        'all'
    );

    // theme js
    wp_enqueue_script(
        'spine-js', 
        assetPath( 'scripts/js/bundle.js' ), 
        ['jquery'], 
        filemtime(get_template_directory() . '/scripts/js/bundle.js'), 
        true
    );

 }, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'spine')
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'gallery', 'search-form']);

    /**
    * remove unwanted menu pages in admin dashboard
    */
    add_action( 'admin_menu', function() {
        remove_menu_page( 'edit.php' );
    	remove_menu_page( 'edit-comments.php' );
    });
});
