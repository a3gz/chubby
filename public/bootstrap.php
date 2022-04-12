<?php
defined('BASE_PATH') || define('BASE_PATH', '/public');
defined('PUBLIC_PATH') || define('PUBLIC_PATH',   __DIR__);
defined('PRIVATE_PATH') || define(
  'PRIVATE_PATH',
  dirname(__DIR__) . '/private'
);
// Directories usually kept in the private directory.
defined('APP_PATH') || define('APP_PATH', PRIVATE_PATH . '/app');
defined('ETC_PATH') || define('ETC_PATH', PRIVATE_PATH . '/etc');
defined('VENDOR_PATH') || define(
  'VENDOR_PATH',
  PRIVATE_PATH . '/vendor'
);
defined('ROUTES_PATH') || define(
  'ROUTES_PATH',
  PRIVATE_PATH . '/app/routes'
);
defined('CONFIG_PATH') || define(
  'CONFIG_PATH',
  PRIVATE_PATH . '/app/config'
);
defined('LOG_PATH') || define('LOG_PATH', PRIVATE_PATH . '/log');

define('CONSOLE_ROUTES_PATH', APP_PATH . '/routes/console');

include VENDOR_PATH . '/autoload.php';
