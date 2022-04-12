<?php
$__sapiName = php_sapi_name();
if (PHP_SAPI !== 'cli') {
  echo "Invalid environment. {$__sapiName}";
  die();
}
define('CONSOLE', true);
unset($__sapiName);

include __DIR__ . '/index.php';

// EOF
