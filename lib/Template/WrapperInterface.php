<?php

namespace Spine\lib\Template;

interface WrapperInterface {

  /**
  * Get wrapper template file
  */

  public function wrap();

  public function unwrap();
  
  public function slug();
}
