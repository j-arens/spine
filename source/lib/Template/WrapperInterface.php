<?php

namespace Spine\Lib\Template;

interface WrapperInterface {

  /**
  * Get wrapper template file
  */

  public function wrap();

  public function unwrap();
  
  public function slug();
}
