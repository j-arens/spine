<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 */

 require __DIR__ . '/vendor/autoload.php';

/**
 * Here's what's happening with these hooks:
 * 1. WordPress detects theme in themes/spine
 * 2. When we activate, we tell WordPress that the theme files are actually in themes/spine/templates
 * 3. When we call get_template_directory() or get_template_directory_uri(), we point it back to themes/spine
 *
 * We do this so that the Template Hierarchy will look in themes/spine/templates for core wordpress theme files.
 * But functions.php, style.css, and index.php are all still located in themes/spine otherwise the theme won't work.
 *
 * get_template_directory()   -> /public_html/wp-content/themes/spine
 * get_stylesheet_directory() -> /public_html/wp-content/themes/spine
 * locate_template()
 * ├── STYLESHEETPATH         -> /public_html/wp-content/themes/spine
 * └── TEMPLATEPATH           -> /public_html/wp-content/themes/spine/templates
 */

add_filter('template', function ($stylesheet) {
    return dirname($stylesheet);
});

add_action('after_switch_theme', function () {
    $stylesheet = get_option('template');
    if (basename($stylesheet) !== 'templates') {
        update_option('template', $stylesheet . '/templates');
    }
});

/**
 * spine includes
 *
 * What you would have normally put in functions.php now gets it's own file
 * in scripts/php and is included here. This is done for seperation and organization.
 *
 * Please note that missing files will produce a fatal error.
 */

$spineIncludes = [
  'helpers',
  'setup',
  'filters',
  'js-to-footer',
  'clean-up',
  'nice-search',
  'disable-trackbacks',
  'nav-titles',
  'custom-login',
  'remove-editor-functions'
];

array_walk($spineIncludes, function ($file) {
    if (!locate_template('/scripts/php/' . $file . '.php', true, true)) {
        trigger_error(sprintf(__('Error locating %s for inclusion', 'spine'), $file), E_USER_ERROR);
    }
});
