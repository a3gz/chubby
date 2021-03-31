<?php

\Fat\Factory\AppFactory::getApp()
  ->get(
    '/hello/{name}',
    \Controllers\HelloController::class . ':hello'
  )->setName('hello');

// EOF
