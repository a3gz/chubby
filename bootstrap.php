<?php
defined('PUBLIC_APP_PATH') || define('PUBLIC_APP_PATH',   __DIR__);
defined('PRIVATE_APP_PATH') || define('PRIVATE_APP_PATH',  PUBLIC_APP_PATH);

// Directories usually kept in the private directory.
defined('APP_PATH') || define('APP_PATH',    PRIVATE_APP_PATH . '/src/app');
defined('VENDOR_PATH') || define('VENDOR_PATH', PRIVATE_APP_PATH . '/src/vendor');
defined('CONFIG_PATH') || define('CONFIG_PATH', PRIVATE_APP_PATH . '/src/app/config');
defined('LOG_PATH') || define('LOG_PATH',    PRIVATE_APP_PATH . '/src/log');

// Special route files locations
defined('ROUTES_PATH') || define('ROUTES_PATH', PRIVATE_APP_PATH . '/src/app/routes');
defined('CONSOLE_ROUTES_PATH') || define(
  'CONSOLE_ROUTES_PATH',
  PRIVATE_APP_PATH . '/src/app/routes/console'
);

include VENDOR_PATH . '/autoload.php';

$__ = [];

$__['cfgPath'] = CONFIG_PATH;
$__['config'] = include($__['cfgPath'] . '/config.php');

$APP = new \Slim\App([ 'settings' => $__['config'] ]);

$__['container'] = $APP->getContainer();
$__['dir'] = scandir($__['cfgPath']);
/**
 * If loading order is important, we can prefix all configuration files (except for config.php) with
 * a number and a dash, like so: 001-file1.php, 002-file2.php, etc.
 * The prefix will be removed so only the part of the name after the first dash will be considered as
 * the file's real name.
 */
sort($__['dir']);
foreach ($__['dir'] as $__fileName) {
  if ((substr($__fileName, 0, 1 ) == '.') || ( $__fileName == 'config.php')) continue;
  $__fullFileName = "{$__['cfgPath']}/{$__fileName}";
  if (is_readable($__fullFileName)) {
    $__fileName = preg_replace('#^[0-9]+-#', '', $__fileName);
    $__key = substr($__fileName, 0, -4); // Remove the file extension
    $__['container'][$__key] = include $__fullFileName;
  }
}
