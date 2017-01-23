<?php

include_once(get_stylesheet_directory() . '/lib/Template.php');

/**
 * Theme assets
 */
 add_action('wp_enqueue_scripts', function () {
     wp_enqueue_style('spine-style', get_stylesheet_uri() );
     wp_enqueue_script('spine-js', asset_path( 'scripts/js/bundle.js' ), ['jquery'], null, true);
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
    function remove_menus() {
    	remove_menu_page( 'edit.php' );
    	remove_menu_page( 'edit-comments.php' );
    }

    add_action( 'admin_menu', 'remove_menus' );
});

/**
 * Register sidebars
 */
// add_action('widgets_init', function () {
//     $config = [
//         'before_widget' => '<section class="widget %1$s %2$s">',
//         'after_widget'  => '</section>',
//         'before_title'  => '<h3>',
//         'after_title'   => '</h3>'
//     ];
//     register_sidebar([
//         'name'          => __('Primary', 'spine'),
//         'id'            => 'sidebar-primary'
//     ] + $config);
//     register_sidebar([
//         'name'          => __('Footer', 'spine'),
//         'id'            => 'sidebar-footer'
//     ] + $config);
// });
