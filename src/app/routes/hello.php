<?php

$APP->get(
  '/hello/{name}',
  \Controllers\HelloController::class . ':hello'
)->setName('hello');

// EOF
