<?php

namespace Spine\lib\Assets;

interface ManifestInterface {

  /**
  * Get the cache-busted filename
  *
  * If the manifest does not have an entry for $file, then return $file
  *
  */

  public function get($file);

  /**
  * Get the asset manifest
  */
  public function getAll();
}
