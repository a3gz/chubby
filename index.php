<?php
include __DIR__ . '/bootstrap.php';

if (defined('CONSOLE') && defined('CONSOLE_ROUTES_PATH')) {
  \Fat\Helpers\Environment::mockConsole();
  \Fat\Factory\AppFactory::loadRoutes(CONSOLE_ROUTES_PATH);
} else {
  \Fat\Factory\AppFactory::loadRoutes(ROUTES_PATH);
}

\Fat\Factory\AppFactory::getApp()->run();
