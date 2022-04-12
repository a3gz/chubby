<?php

namespace Fat\Helpers;

class PluginsLoader {
  public function init() {
    $cacheDef = $this->defineCacheStorage();
    $plugins = $this->readPluginsCache($cacheDef);
    if (!$plugins) {
      $plugins = $this->readPluginsFromDir(
        \Fat\Helpers\Path::makePluginsPath()
      );
      $this->writePluginsCache($plugins, $cacheDef);
    }
    if ($plugins && count(array_keys($plugins))) {
      foreach ($plugins as $key => $absFileName) {
        if (is_readable($absFileName)) {
          @include $absFileName;
        }
      }
    }
  }

  protected function defineCacheStorage() {
    $path = \Fat\Helpers\Path::makeEtcPath('/chubby');
    if (!is_dir($path)) {
      \Fat\Helpers\FileSystem::instance()->createDir($path);
    }
    $fileName = 'plugins-cache.json';
    $absFileName = "{$path}/{$fileName}";
    return (object)[
      'path' => $path,
      'fileName' => $fileName,
      'absFileName' => $absFileName,
    ];
  }

  protected function extractFileHeaders($file, $defaultHeaders) {
    $fp = fopen($file, 'r');
    if ($fp) {
      // Pull only the first 8 KB of the file
      $fileData = fread($fp, 8 * 1024);
      fclose($fp);
    } else {
      $fileData = '';
    }

    // Make sure we catch CR-only line endings.
    $fileData = str_replace("\r", "\n", $fileData);

    $cleanHeaders = [];
    foreach ($defaultHeaders as $field => $regex) {
      $matches = preg_match(
        '/^(?:[ \t]*<\?php)?[ \t\/*#@]*' . preg_quote($regex, '/') . ':(.*)$/mi',
        $fileData,
        $match
      );
      if ($matches && $match[1]) {
        $cleanHeaders[$field] = $match[1];
      }
    }
    return $cleanHeaders;
  }

  protected function extractManifest($mainPluginFileName) {
    $defaultHeaders = [
      'type' => 'type',
      'name' => 'name',
      'version' => 'version'
    ];
    $manifest = $this->extractFileHeaders($mainPluginFileName, $defaultHeaders);

    $r = false;
    if (isset($manifest['type']) && trim($manifest['type']) === 'Plugin') {
      $r = $manifest;
    }
    return $r;
  }

  protected function readPluginsCache($cacheDef) {
    $r = null;
    if (is_readable($cacheDef->absFileName)) {
      $r = json_decode(file_get_contents($cacheDef->absFileName), true);
    }
    return $r;
  }

  protected function readPluginsFromDir($baseDir) {
    $path = \Fat\Helpers\Path::makePluginsPath();
    $dirs = \Fat\Helpers\FileSystem::instance()->getSubDirectories($path);
    $r = [];
    foreach ($dirs as $dirName) {
      $pluginName = basename($dirName);
      $mainPluginFileName = "{$dirName}/{$pluginName}.php";
      if (!is_readable($mainPluginFileName)) {
        continue;
      }
      $pluginManifest = $this->extractManifest($mainPluginFileName);
      if ($pluginManifest !== false) {
        $r[$pluginName] = $mainPluginFileName;
      }
    }
    return $r;
  }

  protected function writePluginsCache($plugins, $cacheDef) {
    file_put_contents($cacheDef->absFileName, json_encode($plugins));
    return $this->readPluginsCache($cacheDef);
  }
}

// EOF
