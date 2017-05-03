<?php

include_once get_template_directory() . '/lib/Asset.php';
include_once get_template_directory() . '/lib/Assets/JsonManifest.php';
include_once get_template_directory() . '/lib/Template.php';


function template($layout = 'base') {
  return Template::$instances[$layout];
}

function template_part($template, array $context = [], $layout = 'base') {
  extract($context);
  include template($layout)->partial($template);
}

function asset_path($filename) {
  static $manifest;
  isset($manifest) || $manifest = new JsonManifest(get_stylesheet_directory() . '/' . Asset::$dist . '/assets.json');
  return (string) new Asset($filename, $manifest);
}

/**
 * Determine whether to show the sidebar
 */
function display_sidebar() {
  static $display;
  isset($display) || $display = !in_array(true, [
    is_404(),
    is_search(),
    is_front_page()
  ]);
  return apply_filters('spine/display_sidebar', $display);
}

/**
 * Page titles
 */
function title() {
  if (is_home()) {
    if ($home = get_option('page_for_posts', true)) {
        return get_the_title($home);
    }
    return __('Latest Posts', 'spine');
  }
  if (is_archive()) {
    return get_the_archive_title();
  }
  if (is_search()) {
    return sprintf(__('Search Results for %s', 'spine'), get_search_query());
  }
  if (is_404()) {
    return __('Not Found', 'spine');
  }
  return get_the_title();
}
