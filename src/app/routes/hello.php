<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * GET hello/{name}
 */
$APP->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');

    $tpl = new \Templates\DefaultTemplate(realpath(dirname(__DIR__)));
    $tpl->define('content', 'views/components/hello')
        ->setData(['name' => $name])
        ->write( $response );
})->setName('hello');

// EOF