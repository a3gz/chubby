<?php 
return function($c) {
    $logFileName = PRIVATE_APP_PATH . DIRECTORY_SEPARATOR . basename(PRIVATE_APP_PATH) . '.log'; 
    $logger = new \Monolog\Logger('pwless');
    $file_handler = new \Monolog\Handler\StreamHandler( $logFileName );
    $logger->pushHandler($file_handler);
    return $logger;   
};

// EOF