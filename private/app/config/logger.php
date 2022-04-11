<?php
return function ($c) {
  $appName =  basename(PUBLIC_PATH);
  $logFileName = dirname(__DIR__) . "/{$appName}.local.log";
  $logger = new \Monolog\Logger($appName);
  $file_handler = new \Monolog\Handler\StreamHandler($logFileName);
  $logger->pushHandler($file_handler);
  return $logger;
};

// EOF
