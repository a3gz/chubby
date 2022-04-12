<?php

namespace Fat\Helpers;

class Plugins {
  const KB_IN_BYTES = 1024;

  protected static function getFileData($file, $defaultHeaders) {
    $fp = fopen($file, 'r');
    if ($fp) {
      // Pull only the first 8 KB of the file
      $fileData = fread($fp, 8 * self::KB_IN_BYTES);
      fclose($fp);
    } else {
      $fileData = '';
    }

    // Make sure we catch CR-only line endings.
    $fileData = str_replace("\r", "\n", $fileData);

    $allHeaders = $defaultHeaders;

    foreach ($allHeaders as $field => $regex) {
      $matches = preg_match(
        '/^(?:[ \t]*<\?php)?[ \t\/*#@]*' . preg_quote($regex, '/') . ':(.*)$/mi',
        $fileData,
        $match
      );
      if ($matches && $match[1]) {
        $allHeaders[$field] = $match[1];
      } else {
        $allHeaders[$field] = '';
      }
    }

    return $allHeaders;
  }

  protected static function getMetadata($file) {
    $data = self::getFileData(
      $file,
      [
        'type' => 'type',
        'name' => 'name',
      ]
    );
    $r = false;

    if (
      isset($data['name']) && !empty($data['name'])
      && isset($data['type']) && trim($data['type']) === 'Plugin'
    ) {
      $r = $data;
    }
    return $r;
  }

  public static function init() {
    $plugins = self::read(\Fat\Helpers\Path::makeAppPath('/plugins'));
    if ($plugins && count($plugins)) {
      foreach ($plugins as $key => $manifestAbsName) {
        @include $manifestAbsName;
      }
    }
  }

  public static function read($base) {
    $dirs = FileSystem::instance()->getSubDirectories($base);
    $r = [];
    foreach ($dirs as $dirName) {
      $mfn = basename($dirName);
      $manifest = "{$dirName}/{$mfn}-manifest.php";
      if (!is_readable($manifest)) {
        continue;
      }
      $meta = self::getMetadata($manifest);
      if ($meta !== false) {
        $slug = basename($dirName);
        $r[$slug] = $manifest;
      }
    }
    return $r;
  }
}

// EOF
