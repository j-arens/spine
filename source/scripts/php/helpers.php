<?php

use Spine\Lib\Asset;
use Spine\Lib\Assets\JsonManifest;
use Spine\Lib\Template;


function template($layout = 'base') {
  return Template::$instances[$layout];
}

function templatePart($template, array $context = [], $layout = 'base') {
  extract($context);
  include template($layout)->partial($template);
}

function assetPath($filename) {
  static $manifest;
  isset($manifest) || $manifest = new JsonManifest(get_stylesheet_directory() . '/' . Asset::$dist . '/assets.json');
  return (string) new Asset($filename, $manifest);
}

/**
 * Determine whether to show the sidebar
 */
function displaySidebar() {
  static $display;
  isset($display) || $display = !in_array(true, [
    is_404(),
    is_search(),
    is_front_page()
  ]);
  return apply_filters('spine/displaySidebar', $display);
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
