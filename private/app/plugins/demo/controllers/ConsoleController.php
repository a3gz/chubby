<?php

namespace Plugins\demo\controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Fat\Factory\AppFactory;

class ConsoleController {
  public function hello(Request $request, Response $response) {
    $app = AppFactory::getApp();
    $logger = $app->getContainer()->get('logger');

    $name = $request->getAttribute('name');
    $logger->notice("Console: Hello {$name}!");
    echo "\nHello {$name}\n";
    return $response;
  }
}

// EOF
