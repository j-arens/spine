<?php

require_once get_template_directory() . '/lib/Assets/ManifestInterface.php';

/**
* Class Template
*/
class Asset {

  public static $dist = '';
  protected $manifest;
  protected $asset;
  protected $dir;

  public function __construct($file, ManifestInterface $manifest = null) {
    $this->manifest = $manifest;
    $this->asset = $file;
  }

  public function __toString() {
    return $this->getUri();
  }

  public function getUri() {
    $file = ($this->manifest ? $this->manifest->get($this->asset) : $this->asset);
    return get_template_directory_uri() . self::$dist . "/$file";
  }
}
