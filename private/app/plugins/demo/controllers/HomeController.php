<?php

namespace Plugins\demo\controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Fat\Helpers\ResponseTools;

class HomeController {
  public function home(Request $request, Response $response) {
    return ResponseTools::withRedirect($response, 'hello/anonymous');
  }
}

// EOF
