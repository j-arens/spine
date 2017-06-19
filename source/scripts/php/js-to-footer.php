<?php

namespace Spine\Scripts\PHP;

/**
* Moves all scripts to wp_footer action
*/

function jsToFooter() {
  remove_action('wp_head', 'wp_print_scripts');
  remove_action('wp_head', 'wp_print_head_scripts', 9);
  remove_action('wp_head', 'wp_enqueue_scripts', 1);
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\jsToFooter');
