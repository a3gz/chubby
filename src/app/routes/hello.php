<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * GET hello/{name}
 */
$APP->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');

    $tpl = new \Templates\DefaultTemplate();
    $tpl->define('content', 'src/app/views/components/hello')
        ->setData(['name' => $name])
        ->write( $response );
});

// EOF