<?php
\Fat\Factory\AppFactory::getApp()
  ->get(
    '/hello/{name}',
    \Controllers\ConsoleController::class . ':hello'
  );

// EOF
