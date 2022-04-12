<?php
include __DIR__ . '/bootstrap.php';

$GLOBALS['_APP_'] = \Fat\Factory\AppFactory::getApp();
if (defined('BASE_PATH')) {
  $GLOBALS['_APP_']->setBasePath(BASE_PATH);
}
$__pluginsLoader = \Fat\Factory\HelpersFactory::makePluginsLoader();
$__pluginsLoader->init();
unset($__pluginsLoader);

if (defined('CONSOLE') && defined('CONSOLE_ROUTES_PATH')) {
  \Fat\Helpers\Environment::mockConsole();
  \Fat\Factory\AppFactory::loadRoutes(CONSOLE_ROUTES_PATH);
} else {
  \Fat\Factory\AppFactory::loadRoutes(ROUTES_PATH);
}

$GLOBALS['_APP_']->run();
