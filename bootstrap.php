<?php
defined('PUBLIC_APP_PATH') || define('PUBLIC_APP_PATH',   __DIR__);
defined('PRIVATE_APP_PATH') || define('PRIVATE_APP_PATH',  PUBLIC_APP_PATH);

defined('THEMES_ROOT') || define(
  'THEMES_ROOT',
  PUBLIC_APP_PATH . '/assets/themes'
);

// Directories usually kept in the private directory.
defined('APP_PATH') || define('APP_PATH', PRIVATE_APP_PATH . '/src/app');
defined('VENDOR_PATH') || define(
  'VENDOR_PATH',
  PRIVATE_APP_PATH . '/src/vendor'
);
defined('ROUTES_PATH') || define(
  'ROUTES_PATH',
  PRIVATE_APP_PATH . '/src/app/routes'
);
defined('CONFIG_PATH') || define(
  'CONFIG_PATH',
  PRIVATE_APP_PATH . '/src/app/config'
);
defined('LOG_PATH') || define('LOG_PATH', PRIVATE_APP_PATH . '/src/log');

define('CONSOLE_ROUTES_PATH', APP_PATH . '/routes/console');

include VENDOR_PATH . '/autoload.php';

\Fat\Factory\AppFactory::create();
