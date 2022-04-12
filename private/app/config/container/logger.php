<?php
return function ($c) {
  $time = time();
  $year = date('Y', $time);
  $month = date('m', $time);
  $day = date('d', $time);
  $hour = date('H', $time);
  $baseDir = \Fat\Helpers\Path::makePrivatePath('/logs'
    . '/' . $year
    . '/' . $month
    . '/' . $day
  );
  if (!is_dir($baseDir)) {
    mkdir($baseDir, 0777, true);
  }
  $fileName = "{$year}m{$month}d{$day}h{$hour}.log";
  $logFileName = "{$baseDir}/{$fileName}";

  $logger = new \Monolog\Logger($appName);
  $file_handler = new \Monolog\Handler\StreamHandler($logFileName);
  $logger->pushHandler($file_handler);
  return $logger;
};

// EOF
