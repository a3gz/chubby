<?php

\Fat\Factory\AppFactory::getApp()
  ->get('/', \Controllers\HomeController::class . ':home');

// EOF
