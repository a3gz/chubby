<?php

namespace Fat\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

class NotFountExceptionHandler {
  public function notFound(
    ServerRequestInterface $request,
    \Throwable $exception,
    bool $displayErrorDetails
  ) {
    $response = new Response();
    $response->getBody()->write('404 NOT FOUND');
    return $response->withStatus(404);
  }
}

// EOF
