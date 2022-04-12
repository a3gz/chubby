<?php

namespace Fat\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Fat\Helpers\Path;

class NotFountExceptionHandler {
  public function notFound(
    ServerRequestInterface $request,
    \Throwable $exception,
    bool $displayErrorDetails
  ) {
    $response = new Response();
    $tpl = new \Templates\DefaultTemplate(Path::makePrivatePath('/app'));
    $tpl->define('content', 'views/components/404.php')
      ->write($response);
    return $response->withStatus(404);
  }
}

// EOF
