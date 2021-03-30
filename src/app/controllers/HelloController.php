<?php

namespace Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Fat\Factory\AppFactory;

class HelloController {
  public function hello(Request $request, Response $response) {
    $app = AppFactory::getApp();
    $logger = $app->getContainer()->get('logger');

    $name = $request->getAttribute('name');
    $logger->notice("Hello {$name}!");
    $tpl = new \Templates\DefaultTemplate(realpath(dirname(__DIR__)));
    $tpl->define('content', 'views/components/hello')
      ->setData(['name' => $name])
      ->write($response);
    return $response;
  }
} // class

// EOF
