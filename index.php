<?php
include __DIR__ . '/bootstrap.php';

if (defined('CONSOLE') && defined('CONSOLE_ROUTES_PATH')) {
  /*
  \Fat\Factory\AppFactory::getApp()->getContainer()->set(
    'environment', function($c) {
      $argv = $GLOBALS['argv'];
      array_shift($argv);
      $pathInfo = implode('/', $argv);
      $env = ['REQUEST_URI' => "/{$pathInfo}"];
      return \Fat\Legacy\Http\Environment::mock($env);
    }
  );
  */
  \Fat\Factory\AppFactory::loadRoutes(CONSOLE_ROUTES_PATH);
} else {
  \Fat\Factory\AppFactory::loadRoutes(ROUTES_PATH);
}

\Fat\Factory\AppFactory::getApp()->run();
