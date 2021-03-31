<?php

namespace Fat\Helpers;

use Psr\Http\Message\ResponseInterface as Response;

class ResponseTools {
  static public function withRedirect(Response $response, $url, $status = null) {
    $clone = clone $response;
    $clone = $clone->withHeader('Location', (string)$url);
    if (!is_null($status)) {
      $clone = $clone->withStatus($status);
    }
    return $clone;
  }
}

// EOF
