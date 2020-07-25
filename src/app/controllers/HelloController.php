<?php

namespace Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class HelloController {
  public function hello(Request $request, Response $response) {
    global $APP;
    $logger = $APP->getContainer()->get('logger');

    $name = $request->getAttribute('name');
    $logger->notice("Hello {$name}!");
    $tpl = new \Templates\DefaultTemplate(realpath(dirname(__DIR__)));
    $tpl->define('content', 'views/components/hello')
      ->setData(['name' => $name])
      ->write($response);
  }
} // class

// EOF
