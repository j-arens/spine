<?php

interface WrapperInterface {

  /**
  * Get wrapper template file
  */
  public function wrap();

  public function unwrap();

  public function slug();
}
