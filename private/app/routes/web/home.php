<?php
global $_APP_;
$_APP_->get('/', \App\controllers\HomeController::class . ':home');
$_APP_->get(
  '/hello/{name}',
  \App\controllers\HelloController::class . ':hello'
)->setName('hello');

// EOF
