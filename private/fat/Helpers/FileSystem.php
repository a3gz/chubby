<?php

namespace Fat\Helpers;

class FileSystem {
  protected $paths = [];

  public function __call($name, $arguments) {
    $url = $this->{$name};
    if ($url) {
      $path = '';
      if (is_array($arguments) && count($arguments) > 0) {
        $path = $arguments[0];
        if (substr($path, 0, 1) !== '/') {
          $path = "/{$path}";
        }
      }
      $url .= $path;
    }
    return $url;
  }

  public function __get($key) {
    $r = $this->paths['root'];
    if (isset($this->paths[$key])) {
      $r = $this->paths[$key];
    }
    return $r;
  }

  public function createDir(string $path, $permissions = 0755) {
    $r = true;
    if (!is_dir($path)) {
      $r = mkdir($path, $permissions, true);
    }
    return $r;
  }

  public function getSubDirectories($base) {
    $dir = scandir($base);
    $r = [];
    foreach ($dir as $name) {
      $absName = "{$base}/{$name}";
      if (is_dir($absName) && substr($name, 0, 1) !== '.') {
        $r[] = $absName;
      }
    }
    return $r;
  }

  public function getFilesInDirectory($base) {
    $dir = scandir($base);
    $r = [];
    foreach ($dir as $fileName) {
      $absName = "{$base}/{$fileName}";
      if (is_file($absName)) {
        $r[] = $absName;
      }
    }
    return $r;
  }

  public function getPaths() {
    return $this->paths;
  }

  static public function instance() {
    static $o = null;
    if ($o === null) {
      $o = new FileSystem();
    }
    return $o;
  }
}

// EOF
