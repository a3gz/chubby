<?php
global $_APP_;
$_APP_->get('/', \Plugins\demo\controllers\HomeController::class . ':home');
$_APP_->get(
  '/hello/{name}',
  \Plugins\demo\controllers\HelloController::class . ':hello'
)->setName('hello');

// EOF
