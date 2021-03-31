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
    $tpl = new \Templates\DefaultTemplate(APP_PATH . '/');
    $tpl->define('content', 'views/components/404.php')
      ->write($response);
    return $response->withStatus(404);
  }
}

// EOF
