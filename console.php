<?php
// Allow only command line calls
$__sapiName = php_sapi_name();
if (PHP_SAPI !== 'cli') {
  echo "Invalid environment. {$__sapiName}";
  die();
}
define('CONSOLE', true);
unset($__sapiName);

include 'index.php';

// EOF