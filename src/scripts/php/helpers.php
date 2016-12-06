<?php

include '../lib/Asset.php';
include '../lib/Assets/JsonManifest.php';
include '../lib/Template.php';

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
  isset($display) || $display = apply_filters('sage/display_sidebar', false);
  return $display;
}

/**
 * Page titles
 */
function title() {
  if (is_home()) {
    if ($home = get_option('page_for_posts', true)) {
        return get_the_title($home);
    }
    return __('Latest Posts', 'sage');
  }
  if (is_archive()) {
    return get_the_archive_title();
  }
  if (is_search()) {
    return sprintf(__('Search Results for %s', 'sage'), get_search_query());
  }
  if (is_404()) {
    return __('Not Found', 'sage');
  }
  return get_the_title();
}
