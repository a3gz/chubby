<?php

namespace Fat\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

class MethodNotAllowedExceptionHandler {
  public function methodNotAllowed(
    ServerRequestInterface $request,
    \Throwable $exception,
    bool $displayErrorDetails
  ) {
    $response = new Response();
    $response->getBody()->write('405 NOT ALLOWED');
    return $response->withStatus(405);
  }
}

// EOF
