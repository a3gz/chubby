<?php

namespace Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class HomeController {
  public function home(Request $request, Response $response) {
    global $APP;
    $router = $APP->getContainer()->get('router');
    return $response->withRedirect(
      $router->pathFor('hello', ['name' => 'anonymous'])
    );
  }
} // class

// EOF
