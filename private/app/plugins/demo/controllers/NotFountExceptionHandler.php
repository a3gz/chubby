<?php

namespace Plugins\demo\controllers;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Fat\Helpers\Path;
use Plugins\demo\templates\DefaultTemplate;

class NotFountExceptionHandler {
  public function notFound(
    ServerRequestInterface $request,
    \Throwable $exception,
    bool $displayErrorDetails
  ) {
    $response = new Response();
    $tpl = new DefaultTemplate(Path::makePluginsPath('/demo'));
    $tpl->define('content', 'views/components/404.php')
      ->write($response);
    return $response->withStatus(404);
  }
}

// EOF
