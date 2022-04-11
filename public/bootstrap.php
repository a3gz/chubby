<?php
defined('PUBLIC_APP_PATH') || define('PUBLIC_APP_PATH',   __DIR__);
defined('PRIVATE_APP_PATH') || define(
  'PRIVATE_APP_PATH',
   dirname(__DIR__) . '/private'
);

defined('THEMES_ROOT') || define(
  'THEMES_ROOT',
  PUBLIC_APP_PATH . '/assets/themes'
);

// Directories usually kept in the private directory.
defined('APP_PATH') || define('APP_PATH', PRIVATE_APP_PATH . '/app');
defined('VENDOR_PATH') || define(
  'VENDOR_PATH',
  PRIVATE_APP_PATH . '/vendor'
);
defined('ROUTES_PATH') || define(
  'ROUTES_PATH',
  PRIVATE_APP_PATH . '/app/routes'
);
defined('CONFIG_PATH') || define(
  'CONFIG_PATH',
  PRIVATE_APP_PATH . '/app/config'
);
defined('LOG_PATH') || define('LOG_PATH', PRIVATE_APP_PATH . '/log');

define('CONSOLE_ROUTES_PATH', APP_PATH . '/routes/console');

include VENDOR_PATH . '/autoload.php';

\Fat\Factory\AppFactory::create();
