<?php

namespace Fat\Helpers;

class Path {
  static public function makeAppPath(string $x = '') {
    if (!empty($x) && substr($x, 0, 1) !== '/') {
      $x = "/{$x}";
    }
    return APP_PATH . $x;
  }

  static public function makeEtcPath(string $x = '') {
    if (!empty($x) && substr($x, 0, 1) !== '/') {
      $x = "/{$x}";
    }
    return ETC_PATH . $x;
  }

  static public function makePluginsPath(string $x = '') {
    if (!empty($x) && substr($x, 0, 1) !== '/') {
      $x = "/{$x}";
    }
    $r = self::makeAppPath("/plugins{$x}");
    return $r;
  }

  static public function makePrivatePath(string $x = '') {
    if (!empty($x) && substr($x, 0, 1) !== '/') {
      $x = "/{$x}";
    }
    return PRIVATE_PATH . $x;
  }

  static public function makePublicPath(string $x = '') {
    if (!empty($x) && substr($x, 0, 1) !== '/') {
      $x = "/{$x}";
    }
    return PUBLIC_PATH . $x;
  }
}

// EOF