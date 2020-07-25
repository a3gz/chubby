<?php
include __DIR__ . '/bootstrap.php';

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
unset($__); // Destroy all the temporary global variables.
$APP->run(); // Run Slim, run!

/***************************************************************/
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

// EOF
