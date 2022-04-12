<?php

namespace Plugins\demo\controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Fat\Factory\AppFactory;
use Plugins\demo\templates\DefaultTemplate;

class HelloController {
  public function hello(Request $request, Response $response) {
    $app = AppFactory::getApp();
    $logger = $app->getContainer()->get('logger');

    $name = $request->getAttribute('name');
    $logger->notice("Hello {$name}!");
    $tpl = new DefaultTemplate(realpath(dirname(__DIR__)));
    $tpl->define('content', 'views/components/hello')
      ->setData(['name' => $name])
      ->write($response);
    return $response;
  }
}

// EOF
