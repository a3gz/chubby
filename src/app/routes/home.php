<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * GET hello/{name}
 */
$APP->get('/', function (Request $request, Response $response) {
    return $response->withRedirect($this->router->pathFor('hello', ['name' => 'anonymous']));
});

// EOF