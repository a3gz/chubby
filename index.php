<?php
include __DIR__ . '/bootstrap.php';

function __chubbyIncludeRoutes($basePath) {
  global $APP;
  $__['dir'] = scandir($basePath);
  foreach($__['dir'] as $__fileName) {
    if (substr($__fileName, 0, 1) == '.') continue;
    $__['fullFileName'] = "{$basePath}/{$__fileName}";
    if (is_dir($__['fullFileName'])) {
      if (!is_readable(realpath("{$__['fullFileName']}/.ignore"))) {
        __chubbyIncludeRoutes($__['fullFileName']);
      }
    } elseif (is_readable($__['fullFileName'])) {
      include $__['fullFileName'];
    }
  }
}

$__ = [];
$__['cfgPath'] = CONFIG_PATH;
$__['config'] = include($__['cfgPath'] . '/config.php');
$__['dir'] = scandir($__['cfgPath']);

$APP = new \Slim\App(['settings' => $__['config']]);

/**
 * If loading order is important, we can prefix all configuration files
 * (except for config.php) with a number and a dash, like so: 001-file1.php,
 * 002-file2.php, etc.
 * The prefix will be removed so only the part of the name after the first dash
 * will be considered as the file's real name.
 */
sort($__['dir']);
$__['container'] = $APP->getContainer();
foreach ($__['dir'] as $__fileName) {
  if ((substr($__fileName, 0, 1 ) == '.') || ( $__fileName == 'config.php')) {
    continue;
  }
  $__fullFileName = "{$__['cfgPath']}/{$__fileName}";
  if (is_readable($__fullFileName)) {
    $__fileName = preg_replace('#^[0-9]+-#', '', $__fileName);
    $__key = substr($__fileName, 0, -4);
    $__['container'][$__key] = include $__fullFileName;
  }
}

if (defined('CONSOLE') && defined('CONSOLE_ROUTES_PATH')) {
  $__['container']['environment'] = function($c) {
    $argv = $GLOBALS['argv'];
    array_shift($argv);
    $pathInfo = implode('/', $argv);
    $env = ['REQUEST_URI' => "/{$pathInfo}"];
    return \Slim\Http\Environment::mock($env);
  };
  $__['path'] = CONSOLE_ROUTES_PATH;
} else {
  $__['path'] = ROUTES_PATH;
}

__chubbyIncludeRoutes($__['path']);
unset($__);
$APP->run();

// EOF
