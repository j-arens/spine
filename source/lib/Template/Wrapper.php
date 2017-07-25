<?php

namespace Spine\lib\Template;

use Spine\lib\Template\WrapperInterface;

// require_once get_template_directory() . '/lib/Template/WrapperInterface.php';

class Wrapper implements WrapperInterface {

  protected $slug;
  protected $template = '';
  protected $wrapper = [];

  /**
  * Wrapper constructor
  */
  public function __construct($template, $base = 'layouts/base.php') {
      $this->slug = sanitize_title(basename($base, '.php'));
      $this->wrapper = [$base];
      $this->template = $template;
      $str = substr($base, 0, -4);
      array_unshift($this->wrapper, sprintf($str . '-%s.php', basename($template, '.php')));
  }

  public function __toString() {
    return $this->unwrap();
  }

  public function wrap() {
    $wrappers = apply_filters('spine/wrap_' . $this->slug, $this->wrapper) ?: $this->wrapper;
    return locate_template($wrappers);
  }

  public function slug() {
    return $this->slug;
  }
  
  public function unwrap() {
    $template = apply_filters('spine/unwrap_' . $this->slug, $this->template) ?: $this->template;
    return locate_template($template) ?: $template;
  }
}
