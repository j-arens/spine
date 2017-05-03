<?php

require_once get_template_directory() . '/lib/Assets/ManifestInterface.php';

class JsonManifest implements ManifestInterface {
  protected $manifest = [];

  /**
  * JsonManifest constructor
  */
  public function __construct($manifestPath) {
    $this->manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
  }

  public function get($file) {
    return isset($this->manifest[$file]) ? $this->manifest[$file] : $file;
  }

  public function getAll(){
    return $this->manifest;
  }
}
