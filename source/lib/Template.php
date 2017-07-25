<?php

namespace Spine\lib;

use Spine\lib\Template\Partial;
use Spine\lib\Template\WrapperInterface;

// require_once get_stylesheet_directory() . '/lib/Template/Partial.php';
// require_once get_stylesheet_directory() . '/lib/Template/WrapperInterface.php';

class Template {

  public static $instances = [];
  protected $wrapper;

  public function __construct(WrapperInterface $wrapper) {
    $this->wrapper = $wrapper;
    self::$instances[$wrapper->slug()] = $this;
  }

  public function layout() {
    return $this->wrapper->wrap();
  }

  public function main() {
    return $this->wrapper->unwrap();
  }

  public function partial($template) {
    return (new Partial($template, $this->main()))->path();
  }
}
