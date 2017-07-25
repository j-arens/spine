<?php

namespace Spine\scripts\php;

/**
* Disables trackbacks/pingbacks
*/

/**
* Disable pingback XMLRPC method
*/
function filterXmlrpcMethod($methods) {
  unset($methods['pingback.ping']);
  return $methods;
}

add_filter('xmlrpc_methods', __NAMESPACE__ . '\\filterXmlrpcMethod', 10, 1);

/**
* Remove pingback header
*/
function filterHeaders($headers) {
  if (isset($headers['X-Pingback'])) {
    unset($headers['X-Pingback']);
  }

  return $headers;
}

add_filter('wp_headers', __NAMESPACE__ . '\\filterHeaders', 10, 1);

/**
* Kill trackback rewrite rule
*/
function filterRewrites($rules) {
  foreach ($rules as $rule => $rewrite) {
    if (preg_match('/trackback\/\?\$$/i', $rule)) {
      unset($rules[$rule]);
    }
  }

  return $rules;
}

add_filter('rewrite_rules_array', __NAMESPACE__ . '\\filterRewrites');

/**
* Kill bloginfo('pingback_url')
*/
function killPingbackUrl($output, $show) {
  if ($show === 'pingback_url') {
    $output = '';
  }

  return $output;
}

add_filter('bloginfo_url', __NAMESPACE__ . '\\killPingbackUrl', 10, 2);

/**
* Disable XMLRPC call
*/
function killXmlrpc($action) {
  if ($action === 'pingback.ping') {
    wp_die('Pingbacks are not supported', 'Not Allowed!', ['response' => 403]);
  }
}

add_action('xmlrpc_call', __NAMESPACE__ . '\\killXmlrpc');
