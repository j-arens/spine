<?php

include_once(get_stylesheet_directory() . '/lib/Template.php');

/**
 * Theme assets
 */
 add_action('wp_enqueue_scripts', function () {
     wp_enqueue_style('theme_style', get_stylesheet_directory() . 'style.css', false, null);
     wp_enqueue_script('theme_js', get_stylesheet_directory() . '/scripts/js/bundle.js', ['jquery'], null, true);
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
     * Use main stylesheet for visual editor
     * @see assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('spine/style.css'));
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
